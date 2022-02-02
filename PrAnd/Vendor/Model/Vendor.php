<?php

namespace PrAnd\Vendor\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\DataObject\IdentityInterface;
use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor as ResourceVendor;

class Vendor extends AbstractExtensibleModel implements IdentityInterface, VendorInterface
{
    /** @var string  */
    const CACHE_TAG = 'vendor_cache_';

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /** @var string  */
    protected $_eventPrefix = 'prand_vendor';

    public function _construct()
    {
        $this->_init(
            ResourceVendor::class
        );
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        $identity = [self::CACHE_TAG . $this->getId()];
        return $identity;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(static::DESCRIPTION);
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->setData(static::DESCRIPTION, $description);
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(static::IMAGE) ?? '';
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->setData(static::IMAGE, $image);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(static::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->setData(static::CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(static::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        $this->setData(static::UPDATED_AT, $updatedAt);
        return $this;
    }

    /**
     * @return array|string[]
     */
    protected function getCustomAttributesCodes(): array
    {
        $this->customAttributesCodes = static::ATTRIBUTES;
        return $this->customAttributesCodes;
    }
}
