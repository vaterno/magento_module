<?php

namespace PrAnd\Vendor\Model\ResourceModel\Vendor\Grid;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\AggregationInterface;
use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor as ResourceVendor;
use PrAnd\Vendor\Model\Vendor;
use Magento\Framework\Api\Search\SearchResultInterface;

class Collection extends AbstractCollection implements SearchResultInterface
{
    /** @var AggregationInterface */
    protected $aggregations;
    protected $searchCriteria;

    /**
     * @var string
     */
    protected $_idFieldName = Vendor::ID;

    public function _construct()
    {
        $this->_init(
            Vendor::class,
            ResourceVendor::class
        );

        $this->addAttributeToSelect('*');
    }

    /**
     * @return SearchCriteriaInterface
     */
    public function getSearchCriteria(): SearchCriteriaInterface
    {
        return $this->searchCriteria;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria): self
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->getSize();
    }

    /**
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount): self
    {
        return $this;
    }

    /**
     * @param array|null $items
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setItems(?array $items = null): self
    {
        if (!empty($items)) {
            /** @var VendorInterface $item */
            foreach ($items as $item) {
                $this->addItem($item);
            }
        }

        return $this;
    }

    /**
     * @return AggregationInterface
     */
    public function getAggregations(): AggregationInterface
    {
        return $this->aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations): self
    {
        $this->aggregations = $aggregations;
        return $this;
    }
}
