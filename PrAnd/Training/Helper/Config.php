<?php

namespace PrAnd\Training\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const PRICE_PREMIUM_ALIAS = 'prand_training_training_settings/general/price_premium';
    const IS_ENABLE_PRICE_PREMIUM_ALIAS = 'prand_training_training_settings/general/is_enabled_price_premium';

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isPremiumPriceEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::IS_ENABLE_PRICE_PREMIUM_ALIAS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return float|int
     */
    public function getPremiumPrice(): float
    {
        return $this->scopeConfig->getValue(
            static::PRICE_PREMIUM_ALIAS,
            ScopeInterface::SCOPE_STORE
        );
    }
}
