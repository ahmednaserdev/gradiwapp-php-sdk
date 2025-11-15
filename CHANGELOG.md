# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-01-27

### Changed
- **BREAKING:** Base URL is now internal and non-configurable. All requests are automatically sent to `https://api.gradiwapp.com/external/v1`
- Removed `baseUrl` parameter from `Config` constructor
- Removed `base_url` from Laravel config file
- Removed `GRADIWAPP_BASE_URL` environment variable
- Added `Config::BASE_URL` public constant

### Migration
See [MIGRATION_v1.0.1.md](MIGRATION_v1.0.1.md) for detailed migration instructions.

## [1.0.0] - 2025-01-27

### Added
- Initial release of GradiWapp PHP SDK
- Support for all message types: text, image, media, location, link, file, contact, reply, both
- Timezone-aware message scheduling with ISO8601 and IANA timezone support
- Webhook management (create, update, delete)
- Message status checking
- HMAC signature authentication
- Framework-agnostic core implementation
- Laravel 7-12 integration with Service Provider and Facade
- Comprehensive error handling with specific exception types
- Full type hints and PHPDoc documentation
- Support for session ID and priority settings

