# Zip Payment for Magento 2

Zip gives customers the ability to shop now and pay later with no interest while you as the merchant get paid immediately. Customers select Zip at checkout, get approved instantly and shop securely.

The extension is available to merchants in Australia.

## Prerequisites

- PHP 8.2 or above
- Composer
- A Zip merchant account and its API keys

## Installation instructions

The extension is a Composer package. It depends on Zip's PHP client library
(`zipmoney/merchantapi-php`), which Composer installs for you — the extension
does not carry a copy of it.

### Install using Composer (recommended)

From your Magento root:

- `composer require zip/magento2`
- `php bin/magento module:enable Zip_ZipPayment`
- `php bin/magento setup:upgrade`
- `php bin/magento setup:di:compile`
- `php bin/magento setup:static-content:deploy`

`php bin/magento module:status` will tell you whether the module is already
enabled.

To upgrade:

- `composer update zip/magento2`
- `php bin/magento setup:upgrade`
- `php bin/magento setup:di:compile`
- `php bin/magento cache:clean`
- `php bin/magento setup:static-content:deploy`

### Install from the release archive

Each release attaches `zip-zippayment-<version>.zip` — the Composer package, with
`composer.json` at its root and no `vendor/`. The Zip PHP library is a declared
dependency, so Composer fetches it:

```bash
composer config repositories.zip artifact /path/to/archives
composer require zip/magento2
```

There is deliberately no archive for copying files into `app/code`. Such a store
needs the library inside `vendor/` and the tree nested under `Zip/ZipPayment/`,
because Magento never reads a module's `composer.json` when the module is dropped
there. If that route is needed, it has to be a separate archive — ask rather than
improvise one from this repository, which carries no library at all.

### Install from Adobe Commerce Marketplace

The extension is also listed on
[Adobe Commerce Marketplace](https://commercemarketplace.adobe.com/zip-magento2.html).
Marketplace delivers the same Composer package through `repo.magento.com`, so the
commands above apply once the extension is added to your Marketplace account.

## Configuration

Request your API keys from Zip at integrations@zip.co.

In the Magento Admin, go to **Stores > Settings > Configuration > Sales > Payment
Methods** and expand the Zip section.

1. Set **Enable** to Yes and enter a **Title** for the payment method, e.g. "Zip".
1. Set **Environment** to Sandbox for a test site or Production for a live one.
1. Enter the **Public Key** and **Private Key**.
1. Set **Region for Widget** to Australia.
1. Set **Capture Method** to Capture to take payment when the order is placed, or to
   Authorise to authorise at checkout and capture later.
1. Set **Log Setting** to Info, or to Debug to log debug information as well.
1. Optionally restrict the payment method by country, product category and minimum
   or maximum order value.
1. Set **Sort Order** to 0 to place Zip at the top of the payment methods.

Banners and widgets for the home, product, category and cart pages are
configured under **Marketing Banners Widgets and Banners** in the same section. At
checkout the Zip payment method always shows its own description and "Learn More"
popup; there is nothing to configure for it.

Click **Save Config**.

## Questions and feedback

If you have any questions about this extension or its implementation, contact
integrations@zip.co.
