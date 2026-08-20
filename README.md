# Magento2 zippayment

Zip gives customers the ability to shop now and pay later with no interest while you as the merchant get paid immediately. Customers select Zip at checkout and get approved instantly and shop securely. Zip is fully integrated with the store’s online checkout and can integrate seamlessly with your store directly via API or by using one of our platform plugins.

## Prerequisites

- PHP 8.1 or above
- Composer
- Configuration credentials

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

### Install using Magento Marketplace

Note:- This extension is not yet available in the Magento Marketplace. This
section will be updated once it is released there.

## Configuration

### Payment Section

1. Contact Zip instegrations support through one of the following channels depending on your region to request your configuration credentials:
    - **UK: integrationsuk@zip.co**
    - **NZ: integrationsnz@zip.co**
    - **AU: integrations@zip.co**
1. Open the Magento Admin
1. Click    the Stores  icon    in  the left    hand menu   and from    there   choose  Settings    >   Configuration
1. The  configuration   page    will    open.   From  the little  menu    on  the left    hand    side    of  this    screen  you must    click   Sales  and then
choose  Payment Methods when    it  expands.
1. On   the Payment Methods page,   click   Other   Payment Methods so  it  expands.

![Alt text](https://static.zip.co/github-images/payment-section-2.jpg "Payment Section")

1. Set Enable  to  Yes and a   title   for the payment method  “Zip"
1. Enter the   Private Key and Public  Key.
1. Set  payment action to Capture, or Authorise if you want to authorise  on checkout completion and capture later

### Note: Authorise is only available for AU region only

1. Set  log settings    to  Info or Debug if you want to log all the debug information as well.
1. Set  environment to  either  Sandbox (for    your    test    or  development site)   or  Production  (for    your    live    website)
1. Set  Sort    Order   to  0 to place the payment method on top.

### Marketing Banners and Widgets Section

![Alt text](https://static.zip.co/github-images/marketing-section.png "Markting Banners and Widgets Section")

1. Scroll down  and expand  Marketing   Banners and Widgets section
1. Expand   everything  and set all options to Yes/No as per your requirement.
1. Click    Save    Config  up  the top

### Questions and feedback

If you have any questions concerning this product or the implementation please contact integrations@zip.co for assistance.
