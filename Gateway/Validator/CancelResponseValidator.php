<?php

namespace Zip\ZipPayment\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class CancelResponseValidator extends AbstractValidator
{

    /**
     * Performs validation of response
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject['response'];

        if (isset($response['api_response']) && is_object($response['api_response'])) {
            if (isset($response['api_response']->error)) {
                return $this->createResult(
                    false,
                    [__('Could not cancel the charge')]
                );
            }
        } else if (isset($response['message'])) {
            return $this->createResult(
                false,
                [__($response['message'])]
            );
        }

        return $this->createResult(true);
    }
}
