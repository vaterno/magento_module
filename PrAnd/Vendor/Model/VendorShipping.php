<?php

namespace PrAnd\Vendor\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;

class VendorShipping extends AbstractCarrier implements CarrierInterface
{
    /** @var string  */
    protected $_code = 'vendorshipping';

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory,
        \Psr\Log\LoggerInterface                                    $logger,
        \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array                                                       $data = []
    )
    {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * Collect and get rates
     *
     * @param RateRequest $request
     * @return Result|bool|null
     */
    public function collectRates(RateRequest $request): ?Result
    {
        if ($this->getConfigFlag('active') && !$this->isInCartExistsProductsWithVendors($request)) {
            return null;
        }

        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();
        $price = $this->getConfigData('shipping_cost');
        $title = $this->getConfigData('title');

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($title);

        $method->setMethod($this->_code);
        $method->setMethodTitle($title);

        $method->setCost($price);
        $method->setPrice($price);
        $result->append($method);

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function isInCartExistsProductsWithVendors(RateRequest $request): bool
    {
        $isExist = false;

        /** @var \Magento\Quote\Model\Quote\Item[] $cartItems */
        $cartItems = $request->getAllItems();

        if (!empty($cartItems)) {
            foreach($cartItems as $cartItem) {
                $cartProductItem = $cartItem->getData('product');

                if (!empty($cartProductItem->getData('prand_vendor'))) {
                    $isExist = true;
                    break;
                }
            }
        }

        return $isExist;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return [
            $this->_code => $this->getConfigData('name')
        ];
    }
}