# Changelog

All notable changes to the Zip Payment module for Magento 2 are documented in this file.

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
