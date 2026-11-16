<?php declare(strict_types=1);

namespace Zip\ZipPayment\Model\Checks;

use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;

class CanUseForQuoteItems implements \Magento\Payment\Model\Checks\SpecificationInterface
{
    /**
     * Check the quote items against the method's quote_items validator, if it has one
     *
     * @param MethodInterface $paymentMethod
     * @param Quote $quote
     * @return bool
     */
    public function isApplicable(MethodInterface $paymentMethod, Quote $quote): bool
    {
        try {
            $quoteItemsValidator = $paymentMethod->getValidatorPool()->get('quote_items');
        } catch (\Throwable $e) {
            return true;
        }

        $result = $quoteItemsValidator->validate(['quote' => $quote]);
        return $result->isValid();
    }
}
