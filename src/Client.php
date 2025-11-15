<?php

namespace GradiWapp\Sdk;

use GradiWapp\Sdk\Exceptions\AuthenticationException;
use GradiWapp\Sdk\Exceptions\GradiWappException;
use GradiWapp\Sdk\Exceptions\HttpException;
use GradiWapp\Sdk\Exceptions\ValidationException;
use GradiWapp\Sdk\Resources\Messages;
use GradiWapp\Sdk\Resources\Webhooks;
use GradiWapp\Sdk\Support\SignatureGenerator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Main SDK Client
 */
class Client
{
    private GuzzleClient $httpClient;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->httpClient = new GuzzleClient([
            'base_uri' => Config::BASE_URL,
            'timeout' => $this->config->getTimeout(),
            'verify' => $this->config->getVerifySsl(),
            'http_errors' => false, // We'll handle errors manually
        ]);
    }

    /**
     * Get Messages resource
     */
    public function messages(): Messages
    {
        return new Messages($this);
    }

    /**
     * Get Webhooks resource
     */
    public function webhooks(): Webhooks
    {
        return new Webhooks($this);
    }

    /**
     * Make HTTP request
     *
     * @param string $method HTTP method
     * @param string $uri URI path
     * @param array $options Request options
     * @return array Decoded JSON response
     * @throws GradiWappException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        // Prepare body
        $body = '';
        if (isset($options['json'])) {
            $body = json_encode($options['json'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $options['body'] = $body;
            unset($options['json']);
        } elseif (isset($options['body'])) {
            $body = is_string($options['body']) ? $options['body'] : json_encode($options['body']);
        }

        // Generate signature and timestamp
        $signature = SignatureGenerator::generate($body, $this->config->getApiSecret());
        $timestamp = SignatureGenerator::generateTimestamp();

        // Set headers
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'X-Api-Key' => $this->config->getApiKey(),
            'X-Api-Secret' => $this->config->getApiSecret(),
            'X-Signature' => $signature,
            'Date' => $timestamp,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        try {
            $response = $this->httpClient->request($method, $uri, $options);
            return $this->handleResponse($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                return $this->handleResponse($response);
            }
            throw new HttpException(
                'Request failed: ' . $e->getMessage(),
                0,
                $e
            );
        } catch (GuzzleException $e) {
            throw new HttpException(
                'HTTP request failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Handle HTTP response and throw appropriate exceptions
     *
     * @param ResponseInterface $response
     * @return array Decoded JSON response
     * @throws GradiWappException
     */
    private function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $rawBody = $response->getBody()->getContents();

        // Handle empty body
        if (empty($rawBody)) {
            if ($statusCode >= 200 && $statusCode < 300) {
                return ['success' => true, 'data' => null];
            }
            throw new HttpException('Empty response body', $statusCode);
        }

        $data = json_decode($rawBody, true);

        // If JSON decode failed, use raw body
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = ['success' => false, 'message' => 'Invalid JSON response', 'raw' => $rawBody];
        }

        // Extract BaseResponse fields
        $success = $data['success'] ?? false;
        $message = $data['message'] ?? 'Unknown error';
        $errors = $data['errors'] ?? null;
        $meta = $data['meta'] ?? null;

        // Handle errors based on status code
        if ($statusCode >= 200 && $statusCode < 300) {
            if ($success) {
                return $data;
            }
            // API returned success=false but 2xx status
            throw new HttpException($message, $statusCode, null, $errors, $meta, $rawBody);
        }

        // Handle specific error codes
        if ($statusCode === 401 || $statusCode === 403) {
            throw new AuthenticationException($message, $statusCode, null, $errors, $meta, $rawBody);
        }

        if ($statusCode === 422) {
            throw new ValidationException($message, $statusCode, null, $errors, $meta, $rawBody);
        }

        // Generic HTTP error
        throw new HttpException($message, $statusCode, null, $errors, $meta, $rawBody);
    }
}

