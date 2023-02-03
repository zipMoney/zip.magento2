#!/bin/bash
set -o errexit
set -o nounset
set -o pipefail
# set -o xtrace # Uncomment this line for debugging purposes

# Load Magento environment
. /opt/bitnami/scripts/magento-env.sh

# Load libraries
. /opt/bitnami/scripts/libbitnami.sh
. /opt/bitnami/scripts/liblog.sh
. /opt/bitnami/scripts/libwebserver.sh

print_welcome_page

if [[ "$1" = "/opt/bitnami/scripts/magento/run.sh" || "$1" = "/opt/bitnami/scripts/$(web_server_type)/run.sh" || "$1" = "/opt/bitnami/scripts/nginx-php-fpm/run.sh" ]]; then
    info "** Starting Magento setup **"
    /opt/bitnami/scripts/"$(web_server_type)"/setup.sh
    /opt/bitnami/scripts/php/setup.sh
    /opt/bitnami/scripts/mysql-client/setup.sh
    /opt/bitnami/scripts/magento/setup.sh
    /post-init.sh
    info "** Magento setup finished! **"
    
    cd /bitnami/magento

    if [[ ! -f ".initialised" ]]; then
        mkdir -p app/code/Zip/ZipPayment
        ln -s ../magento-zip app/code/Zip/ZipPayment
        php bin/composer config http-basic.repo.magento.com $MAGE_REPO_PUBLIC_KEY $MAGE_REPO_PRIVATE_KEY
        php bin/magento deploy:mode:set developer
        php bin/magento sampledata:deploy
        php bin/magento module:enable Zip_ZipPayment
        php bin/magento setup:upgrade
        php bin/magento cache:clean
        php bin/magento cache:flush
        php bin/magento config:set payment/zippayment/active 1
        php bin/magento config:set payment/zippayment/title "Zip now, pay later"
        php bin/magento config:set payment/zippayment/environment sandbox
        php bin/magento config:set payment/zippayment/merchant_public_key $ZIPMONEY_SANDBOX_PUBLIC_KEY
        php bin/magento config:set payment/zippayment/merchant_private_key $ZIPMONEY_SANDBOX_PRIVATE_KEY
        php bin/magento config:set payment/zippayment/widget_region au
        php bin/magento config:set payment/zippayment/payment_action capture
        php bin/magento config:set payment/zippayment/display_widget_mode iframe
        touch .initialised
    fi
fi

echo ""
exec "$@"


