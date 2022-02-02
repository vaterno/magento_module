<?php

namespace PrAnd\Vendor\Api;

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use PrAnd\Vendor\Api\Data\VendorInterface as VendorEntity;
use PrAnd\Vendor\Model\ResourceModel\Vendor as VendorResource;

interface VendorRepositoryInterface
{
    /**
     * @param int $id
     * @return VendorEntity
     */
    public function getById(int $id): VendorEntity;

    /**
     * @param SearchCriteria $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteria $searchCriteria): SearchResultsInterface;

    /**
     * @param array $ids
     * @return VendorEntity[]
     */
    public function getByIds(array $ids): array;

    /**
     * @param VendorEntity $vendor
     * @return VendorResource
     */
    public function saveOrUpdate(VendorEntity $vendor): VendorResource;

    /**
     * @param VendorEntity|AbstractExtensibleModel $vendor
     * @return boolean
     */
    public function delete(VendorEntity $vendor): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
