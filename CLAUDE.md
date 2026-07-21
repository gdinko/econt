# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

`gdinko/econt` is a Laravel package (PHP library, not an application) that wraps the
[Econt](http://ee.econt.com/services/) shipping/courier JSON API — nomenclatures (countries,
cities, offices, streets, quarters), shipping labels, courier requests, shipment tracking, client
profiles, and payment reports. It ships an `Econt` facade, Artisan sync/tracking commands, and
optional Eloquent models/migrations for caching nomenclature data locally.

## Commands

```bash
composer install          # install dependencies
composer test              # run the Pest test suite (vendor/bin/pest)
composer test-coverage     # run tests with coverage
composer analyse            # run PHPStan (vendor/bin/phpstan analyse), level 4, see phpstan.neon.dist
vendor/bin/php-cs-fixer fix --config=.php_cs.dist.php   # fix code style (PSR-12 + rules below)
```

Run a single test with Pest, e.g.:

```bash
vendor/bin/pest tests/LabelsTest.php
vendor/bin/pest --filter "Can Create Label"
```

There is no build step — this is a plain Composer/PSR-4 library (`Gdinko\Econt\` → `src/`).

### Important: tests hit the real Econt demo API

`tests/TestCase.php` boots an Orchestra Testbench app with the package's service provider and an
in-memory SQLite database, but `MakesHttpRequests::request()` is never faked — tests call
`Econt::createLabel()`, etc. and go over the network to the configured `econt.env=test` base URL
(Econt's demo environment, using the default `iasp-dev`/`iasp-dev` credentials from
`config/econt.php`). Running the suite therefore requires network access to Econt's demo API, and
some tests (e.g. `LabelsTest`) depend on side effects of earlier tests in the same file (a label
created in one `it()` is updated/deleted/tracked in later ones) rather than using mocks/fixtures.

## Architecture

**Entry point**: `Econt::` facade (`src/Facades/Econt.php`) resolves the `econt` singleton bound
in `EcontServiceProvider::register()` to the `Gdinko\Econt\Econt` class (`src/Econt.php`).

**`Econt` class composition** — `src/Econt.php` holds only account/base-URL/timeout state
(supports multiple named accounts via `addAccountToStore()` / `setAccountFromStore()`, keyed by
`Str::slug($user)`) and pulls in all API behavior via traits:
- `MakesHttpRequests` (`src/MakesHttpRequests.php`) — thin wrapper over Laravel's `Http` facade
  (`get`/`post`/`put` → `request()`); applies basic auth, timeout, and base URL, and rethrows
  failures as `EcontException` with the decoded JSON error body attached.
- `src/Actions/*` — one trait per Econt service area, each method maps 1:1 to an Econt JSON-RPC
  style endpoint (e.g. `Nomenclatures/NomenclaturesService.getCities.json`) and unwraps the
  relevant key from the response (`ManagesNomenclatures`, `ManagesLabels`, `ManagesShipments`,
  `ManagesProfile`, `ManagesPaymentReports`).

**Hydrators** (`src/Hydrators/*`, e.g. `Label`, `Labels`, `Courier`, `Payment`, `Address`) are the
request-shaping layer: each wraps a raw array payload, defines Laravel `Validator` rules in
`validationRules()`, and exposes `validated()` which either returns the sanitized array (in the
shape the Econt API expects) or throws `EcontValidationException` with structured errors. Actions
in `src/Actions/*` only accept these Hydrator objects, never raw arrays, so all outbound payload
validation is centralized here.

**Two-layer exception model**: `EcontException` (HTTP/API-level failures, carries `getErrors()`
from the decoded response) vs `EcontValidationException` (local Hydrator validation failures) vs
`EcontImportValidationException` (failures importing API data into local Eloquent models, carries
both `getErrors()` and `getData()`). Catch the specific type — code shouldn't assume a bare
`EcontException` covers validation failures too.

**Local caching layer (optional)**: `src/Models/*` are Eloquent models (`CarrierEcontCountry`,
`CarrierEcontCity`, `CarrierEcontOffice`, `CarrierEcontStreet`, `CarrierEcontQuarter`,
`CarrierEcontPayment`, `CarrierEcontApiStatus`, `CarrierEcontTracking`, `CarrierCityMap`) backed
by `database/migrations/*`, used to mirror Econt nomenclature/tracking data locally. They are
populated by the `econt:sync-*` Artisan commands in `src/Commands/*`, each of which: calls the
matching `Econt::get*()` method, validates each row via the `ValidatesImport` trait
(`src/Traits/ValidatesImport.php`, same `Validator`-based pattern as Hydrators but throwing
`EcontImportValidationException`), then truncates/upserts the corresponding model. Consumers can
publish and override these models/migrations/commands via the `econt-models` /
`econt-migrations` / `econt-commands` `vendor:publish` tags — `EcontServiceProvider` registers all
of these plus the `econt-config` tag.

**Tracking flow is host-app-driven by design**: `TrackCarrierEcontBase`
(`src/Commands/TrackCarrierEcontBase.php`) is abstract — it defines the `econt:track` command
signature/handle logic (account switching, optional stale-record clearing via `--clear`, calling
`Econt::getShipmentStatuses()`, upserting `CarrierEcontTracking`, dispatching
`CarrierEcontTrackingEvent`) but leaves `setup()` (which populates `$this->parcels`) for the
consuming Laravel app to implement in its own `app/Console/Commands` command, per the README's
"Parcels Tracking" section. It is intentionally *not* registered in `EcontServiceProvider`.

**Config** (`config/econt.php`) resolves `env`, `user`/`pass`, `test-base-url` /
`production-base-url`, and `timeout` from `ECONT_*` env vars; `Econt::configBaseUrl()` switches
base URL based on `econt.env` (`test` vs anything else = production).

## Code style

PHP-CS-Fixer config (`.php_cs.dist.php`) enforces PSR-12 plus: short array syntax, alphabetically
ordered imports, no unused imports, one trait-use per statement, trailing commas in multiline
calls, and fully-multiline argument lists once a call wraps. PHPStan runs at level 4 against
`src`, `config`, and `database` (see `phpstan.neon.dist`); `phpstan-baseline.neon` is currently
empty, so don't add new baseline-only suppressions without reason.
