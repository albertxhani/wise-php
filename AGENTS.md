# AGENTS.md

## Project facts

- Language: PHP
- Package type: Composer library
- Minimum runtime: PHP 8.4 (`composer.json` has `"php": "^8.4"`)
- HTTP client dependency: `guzzlehttp/guzzle ^7.0`
- Test framework: PHPUnit `^11.0`

## Before making changes

- Read `composer.json` and `README.md` first for current constraints.
- Prefer minimal, targeted edits over broad refactors.
- Keep public API behavior stable unless explicitly asked to change it.

## Key conventions and gotchas

- Service classes are exposed through `TransferWise\Factory\ServiceFactory`.
- Keep PSR-4 class/file mapping exact (`TransferWise\\...` maps to `src/...`).
  - Example: `TransferWise\Service\ProfileWebhookService` must live in `src/Service/ProfileWebhookService.php`.
- `TransferWise\Client` uses `request(...)` for HTTP calls. Do not introduce `require(...)`.
- Avoid deprecated reflection patterns on PHP 8.5 (e.g. `ReflectionProperty::setAccessible()` in tests).

## Testing and verification

- Install deps: `composer install`
- Run tests: `vendor/bin/phpunit`
- CI runs tests on PHP 8.4 and 8.5 (`.github/workflows/ci.yml`).
- If you touch runtime logic, add/update unit tests in `tests/`.

## Repo hygiene

- Do not commit secrets (`.env`, tokens, credentials).
- Respect `.gitignore` (especially `vendor/`, PHPUnit cache, editor files).
- Do not edit generated dependency internals directly under `vendor/`.

