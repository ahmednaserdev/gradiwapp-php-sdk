<?php

namespace GradiWapp\Sdk\Exceptions;

use Exception;

/**
 * Base exception for all GradiWapp SDK exceptions
 */
class GradiWappException extends Exception
{
    protected ?array $errors = null;
    protected ?array $meta = null;
    protected ?string $rawBody = null;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        ?array $errors = null,
        ?array $meta = null,
        ?string $rawBody = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
        $this->meta = $meta;
        $this->rawBody = $rawBody;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getMeta(): ?array
    {
        return $this->meta;
    }

    public function getRawBody(): ?string
    {
        return $this->rawBody;
    }
}

