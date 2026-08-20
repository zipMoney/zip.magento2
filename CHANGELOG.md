# Changelog

All notable changes to the Zip Payment module for Magento 2 are documented in this file.

## 1.3.0

### Changed
- **The Zip Merchant API library now comes from Composer instead of a copy in the
  tree.** `MerchantApi/` held a hard copy of the library, 47 files of it. A copy
  cannot receive upstream fixes unless somebody ports them by hand, and this one had
  drifted in both directions — ahead of the published package in two places, behind
  it on the PHP 8 string casts, on retrying 5xx responses rather than only transport
  failures, on `currency` for refunds and on `is_partial_capture` for captures. The
  module now requires `zipmoney/merchantapi-php` and the version is a fact in
  `composer.json` rather than a question about who last edited the tree.

  **This changes how the module is installed.** `vendor/` is no longer committed, so
  a checkout or an archive needs `composer install` before the module will load. An
  installation that copies files into place without running Composer will not find
  the library. See the README for the two supported routes.

- **Zip Merchant API library moved to 1.0.24.** Beyond the fixes listed under 1.2.14,
  this brings retry on 5xx with a back-off between attempts, `currency` on refund
  requests and `is_partial_capture` on captures. 1.0.24 itself only trims the
  published archive — it no longer ships the library's own build tooling and tests —
  so nothing in it changes behaviour.

### Fixed
- **The Zip widget did not render, on product pages or at checkout.** The
  Content Security Policy whitelist allowed `static.zip.co`, which is where the
  widget's entry point lives, but the widget is code-split and loads its chunks from
  `bpi.zip.co`. A host entry matches that host and not its subdomains, so the first
  lazy import was blocked and the widget died with `ChunkLoadError`. The banner and
  the "Learn More" link were both lost; payments were never affected.

  Three policies that were missing entirely are added at the same time, from
  violations Magento was already reporting: `connect-src` for the widget's template
  and telemetry, and `style-src` and `font-src` for its stylesheet and fonts. A store
  running CSP in enforcing rather than report-only mode would otherwise have lost the
  widget for a second, independent reason.

  Not covered: the widget also fetches merchant configuration from a CloudFront
  distribution whose hostname is not stable, so it cannot be whitelisted by name, and
  allowing all of `*.cloudfront.net` in a payment module is too broad a trade for one
  configuration file. Under an enforcing policy that request is blocked and the widget
  falls back to its defaults.

## 1.2.14

### Fixed
- **ZES-91: fatal `TypeError` on PHP 8 when a non-string reached a length check.**
  The bundled Merchant API library validated field lengths with bare `strlen()` and
  `strtolower()` calls. PHP 8 stopped coercing those arguments, so passing an integer
  — an order or cart id, for instance — into one of the Model setters threw a
  `TypeError` and could take checkout down. All 60 call sites in `MerchantApi/` now
  cast the value first. The cast is a no-op for values that are already strings, so
  nothing changes for callers that work today.

  Beyond the setters this also covers `listInvalidProperties()` and `valid()`, where
  several `Address` checks (`line1`, `city`, `state`, `postal_code`, `country`) carry
  no `is_null` guard and would otherwise reach `strlen(null)` — deprecated since PHP
  8.1 — and `strlen($apiKey)` in `MerchantApi/Lib/Api/*`, where the key is null until
  one is configured.

  Matches `zipmoney/merchantapi-php` 1.0.21, which carries the same fix for the
  plugins that pull the library through Composer.

### Changed
- **Brought two fixes back from `zipmoney/merchantapi-php` 1.0.22.** The bundled
  library is a hard copy, so improvements made upstream do not arrive on their
  own. A failed call now reports what Zip actually said — the message or details
  from the response body — instead of a bare `[500] Error connecting to the API`,
  which told the merchant nothing about why a payment was refused. And a
  transport-level cURL failure no longer hands `substr()` a boolean; the
  `http_code === 0` path turns it into a proper `ApiException` as intended.

  Going the other way, the retry policy this module gained in ZES-49 — retrying
  5xx and backing off between attempts — is now in the library too, so the
  plugins that consume it through Composer are no longer behind this one.

## 1.2.13

### Fixed
- **MIA-63: duplicate orders.** A session lock and a deterministic idempotency key
  prevent the same checkout from creating more than one order.

### Changed
- A Composer lockfile is committed so dependency scanning (Aikido) has something to
  read.
- A pipeline job stands a branch of this repository up on the Magento QA store
  (ZES-86).

## 1.2.12

### Fixed
- **ZES-88: Order left in `pending` when a Zip charge fails.** The error-handling
  guard in `Model/Charge::charge()` used `||` where it should have used `&&`, so the
  condition was always true and the method rethrew the exception before reaching the
  order-cancellation logic. As a result, a failed charge (e.g. HTTP 402 "account is
  locked") left a `pending` order in Magento with no matching successful charge. The
  order is now cancelled when the charge cannot be completed.
- `Helper\Data::handleException()` returned an imploded string while all five call
  sites destructured it with `list($apiError, $message, $logMessage)`, which yielded
  `null` values. It now returns the array again, so the correct decline message
  (e.g. "The payment was declined by Zip.") reaches the customer and the logs.
