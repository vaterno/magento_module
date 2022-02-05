<?php

namespace PrAnd\Vendor\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Ups\Helper\Config;
use Magento\Framework\Xml\Security;
use Magento\Store\Model\ScopeInterface;

class Carrier extends AbstractCarrierOnline implements CarrierInterface
{
    /** @var string  */
    protected $_code = 'vendorshipping';

    /** @var string  */
    protected $configTitlePath = 'carriers/vendorshipping/title';

    /** @var string  */
    protected $configMethodNamePath = 'carriers/vendorshipping/name';

    /** @var string  */
    protected $configShippingCostPath = 'carriers/vendorshipping/shipping_cost';

    /** @var string  */
    protected $configApplicableCountriesPath = 'carriers/vendorshipping/sallowspecific';

    protected $_request;
    protected $_result;
    protected $_baseCurrencyRate;
    protected $_localeFormat;
    protected $_logger;
    protected $configHelper;
    protected $_errors = [];
    protected $_isFixed = true;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param Security $xmlSecurity
     * @param \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory
     * @param \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Directory\Helper\Data $directoryData
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        Config $configHelper,
        array $data = []
    ) {
        $this->_localeFormat = $localeFormat;
        $this->configHelper = $configHelper;

        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
    }

    /**
     * @param \Magento\Framework\DataObject $request
     * @return \Magento\Framework\DataObject|void
     */
    protected function _doShipmentRequest(\Magento\Framework\DataObject $request)
    {}

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return [];
    }

    /**
     * Collect and get rates
     *
     * @param RateRequest $request
     * @return Result|bool|null
     */
    public function collectRates(RateRequest $request): ?Result
    {
        if (!$this->isInCartExistsProductsWithVendors($request)) {
            return null;
        }

        $result = $this->_rateFactory->create();
        $method = $this->_rateMethodFactory->create();
        $price = $this->_scopeConfig->getValue($this->configShippingCostPath, ScopeInterface::SCOPE_STORE);
        $title = $this->_scopeConfig->getValue($this->configTitlePath, ScopeInterface::SCOPE_STORE);

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
     * @param \Magento\Framework\DataObject $request
     * @return bool
     */
    public function proccessAdditionalValidation(\Magento\Framework\DataObject $request): bool
    {
        return true;
    }
}