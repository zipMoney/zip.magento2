define([
        'jquery',
        'uiComponent',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals'
    ], function ($, Component, ko, quote, totals) {
        'use strict';
        return Component.extend({
            initialize: function () {
                this._super();
            },
            getTotalAmount:function(){
                console.log(totals.totals());
                return parseFloat(totals.totals()['grand_total']);
            },
            getCurrencyCode:function () {
                return totals.totals()['quote_currency_code'];
            }
        });
    }
);
