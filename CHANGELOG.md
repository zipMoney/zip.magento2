# Changelog

All notable changes to the Zip Payment module for Magento 2 are documented in this file.

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
