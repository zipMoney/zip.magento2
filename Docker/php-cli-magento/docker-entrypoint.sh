#!/bin/bash

[ "$DEBUG" = "true" ] && set -x

if [ ! -z "${CRONTAB}" ]; then
    echo "${CRONTAB}" > /etc/cron.d/magento && touch /var/log/cron.log
fi

PHP_EXT_DIR=/usr/local/etc/php/conf.d

# Enable PHP extensions
PHP_EXT_COM_ON=docker-php-ext-enable

[ -d ${PHP_EXT_DIR} ] && rm -f ${PHP_EXT_DIR}/docker-php-ext-*.ini

if [ -x "$(command -v ${PHP_EXT_COM_ON})" ] && [ ! -z "${PHP_EXTENSIONS}" ]; then
  ${PHP_EXT_COM_ON} ${PHP_EXTENSIONS}
fi

composer config --global http-basic.repo.magento.com ${MAGE_REPO_PUBLIC_KEY} ${MAGE_REPO_PRIVATE_KEY}
chown -R www:www ${COMPOSER_HOME}

# Run first time setup only if composer.json doesn't already exist
if [ ! -f "/app-dest/composer.json" ]; then
    rm -rf /app/*

    echo "Running composer install"

    composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition .

    echo "Running setup for Magento" 

    install_flags=(
        "--no-interaction"
        "--base-url" "http://${MAGENTO_HOST}:${MAGENTO_EXTERNAL_HTTP_PORT}"
        "--db-host" "${MAGENTO_DATABASE_HOST}:${MAGENTO_DATABASE_PORT}"
        "--db-name" "${MAGENTO_DATABASE_NAME}"
        "--db-user" "${MAGENTO_DATABASE_USER}"
        "--db-password" "${MAGENTO_DATABASE_PASSWORD}"
        "--admin-firstname" "${MAGENTO_ADMIN_FIRSTNAME}"
        "--admin-lastname" "${MAGENTO_ADMIN_LASTNAME}"
        "--admin-email" "${MAGENTO_ADMIN_EMAIL}"
        "--admin-user" "${MAGENTO_ADMIN_USERNAME}"
        "--admin-password" "${MAGENTO_ADMIN_PASSWORD}"
        "--use-rewrites" "1"
        "--backend-frontname" "admin"
        "--search-engine" "elasticsearch7"
        "--elasticsearch-host" "${ELASTICSEARCH_HOST}"
        "--elasticsearch-port" "${ELASTICSEARCH_PORT}"
    )

    php bin/magento setup:install "${install_flags[@]}"
    php bin/magento sampledata:deploy
    php bin/magento setup:upgrade

    mkdir -p app/code/Zip/ZipPayment
    cp -R /magento-zip/* app/code/Zip/ZipPayment/
    chown -R www:www app/code

    php bin/magento module:disable Magento_TwoFactorAuth
    php bin/magento module:enable Zip_ZipPayment
    php bin/magento setup:upgrade
    php bin/magento config:set payment/zippayment/active 1
    php bin/magento config:set payment/zippayment/title "Zip now, pay later"
    php bin/magento config:set payment/zippayment/environment sandbox
    php bin/magento config:set payment/zippayment/merchant_public_key $ZIPMONEY_SANDBOX_PUBLIC_KEY
    php bin/magento config:set payment/zippayment/merchant_private_key $ZIPMONEY_SANDBOX_PRIVATE_KEY
    php bin/magento config:set payment/zippayment/widget_region au
    php bin/magento config:set payment/zippayment/payment_action capture
    php bin/magento config:set payment/zippayment/display_widget_mode iframe
    php bin/magento indexer:reindex
    php bin/magento cache:clean
    php bin/magento cache:flush

    rm -rf app/code/Zip

    echo "Installation complete, moving Magento persistance"
    tar c . | (cd /app-dest && tar -xf -)
fi

exec "$@"