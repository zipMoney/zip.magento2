/**
 * Zip_ZipPayment JS Component
 *
 * @category    ZipMoney
 * @package     Zip_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright   ZipMoney (http://zipmoney.com.au)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (Component, rendererList) {
        'use strict';
        rendererList.push(
            {
                type: 'zippayment',
                component: 'Zip_ZipPayment/js/view/payment/method-renderer/zip-zippayment'
            }
        );
        return Component.extend({});
    }
);
