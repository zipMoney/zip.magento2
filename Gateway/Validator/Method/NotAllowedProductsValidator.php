<?php declare(strict_types=1);

namespace Zip\ZipPayment\Gateway\Validator\Method;

class NotAllowedProductsValidator extends \Magento\Payment\Gateway\Validator\AbstractValidator
{
    /**
     * @var \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider
     */
    private \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider $notAllowedProductsProvider;

    /**
     * @param \Magento\Payment\Gateway\Validator\ResultInterfaceFactory $resultFactory
     * @param \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider $notAllowedProductsProvider
     */
    public function __construct(
        \Magento\Payment\Gateway\Validator\ResultInterfaceFactory $resultFactory,
        \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider $notAllowedProductsProvider
    ) {
        parent::__construct($resultFactory);
        $this->notAllowedProductsProvider = $notAllowedProductsProvider;
    }

    /**
     * Fail validation when the quote holds a product from an excluded category
     *
     * @param array $validationSubject
     * @return \Magento\Payment\Gateway\Validator\ResultInterface
     */
    public function validate(array $validationSubject): \Magento\Payment\Gateway\Validator\ResultInterface
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $validationSubject['quote'];

        $disallowedProductsIds = $this->notAllowedProductsProvider->provideIds((int)$quote->getStoreId());
        $disallowedProductsIdsAsKeys = array_flip($disallowedProductsIds);

        foreach ($quote->getItems() ?? [] as $item) {
            if (isset($disallowedProductsIdsAsKeys[(int)$item->getProductId()])) {
                return $this->createResult(false);
            }
        }
        return $this->createResult(true);
    }
}
