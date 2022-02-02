<?php

namespace PrAnd\Vendor\Model;

use Magento\Eav\Model\Api\SearchCriteria\CollectionProcessor\FilterProcessor;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Model\AbstractExtensibleModel;

use PrAnd\Vendor\Api\Data\VendorCollectionInterface;
use PrAnd\Vendor\Api\Data\VendorCollectionInterfaceFactory;
use PrAnd\Vendor\Api\Data\VendorInterface as VendorEntity;
use PrAnd\Vendor\Api\VendorRepositoryInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor as VendorResource;
use PrAnd\Vendor\Model\ResourceModel\Vendor\Collection as VendorCollection;

class VendorRepository implements VendorRepositoryInterface
{
    /** @var VendorFactory  */
    protected $vendorFactory;

    /** @var VendorResource  */
    protected $vendorResource;

    /** @var CollectionProcessorInterface  */
    protected $collectionProcessor;

    /** @var SearchCriteriaBuilder  */
    protected $searchCriteriaBuilder;

    /** @var VendorCollectionInterfaceFactory  */
    protected $vendorCollectionInterfaceFactory;

    /** @var SearchResultsInterfaceFactory  */
    protected $searchResultFactory;

    /** @var FilterProcessor  */
    protected $filterProcessor;

    /**
     * VendorRepository constructor.
     *
     * @param VendorResource $vendorResource
     * @param VendorFactory $vendorFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param VendorCollectionInterfaceFactory $vendorCollectionInterfaceFactory
     * @param SearchResultsInterfaceFactory $searchResultFactory
     * @param FilterProcessor $filterProcessor
     */
    public function __construct(
        VendorResource $vendorResource,
        VendorFactory $vendorFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        VendorCollectionInterfaceFactory $vendorCollectionInterfaceFactory,
        SearchResultsInterfaceFactory $searchResultFactory,
        FilterProcessor $filterProcessor
    ) {
        $this->vendorResource = $vendorResource;
        $this->vendorFactory = $vendorFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->vendorCollectionInterfaceFactory = $vendorCollectionInterfaceFactory;
        $this->searchResultFactory = $searchResultFactory;
        $this->filterProcessor = $filterProcessor;
    }

    /**
     * @param int $id
     * @return VendorEntity
     */
    public function getById(int $id): VendorEntity
    {
        /** @var VendorEntity|AbstractExtensibleModel $vendorDto */
        $vendorDto = $this->vendorFactory->create();
        $this->vendorResource->load($vendorDto, $id);

        return $vendorDto;
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @return SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteria $searchCriteria): SearchResultsInterface
    {
        /** @var VendorCollectionInterface|VendorCollection $vendorCollection */
        $vendorCollection = $this->vendorCollectionInterfaceFactory->create();
        $vendorCollection->addAttributeToSelect('*');
        $this->filterProcessor->process($searchCriteria, $vendorCollection);

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($vendorCollection->getItems());
        $searchResult->setTotalCount($vendorCollection->getSize());

        return $searchResult;
    }

    /**
     * @param array $ids
     * @return array|VendorEntity[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByIds(array $ids): array
    {
        $result = [];

        if (!empty($ids)) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('entity_id', $ids, 'in')
                ->create();
            $result = $this->getList($searchCriteria)->getItems();
        }

        return $result;
    }

    /**
     * @param VendorEntity $vendor
     * @return VendorResource
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function saveOrUpdate(VendorEntity $vendor): VendorResource
    {
        // update
        if (!empty($vendor->getId())) {
            /** @var Vendor $vendorToUpdate */
            $vendorToUpdate = $this->getById($vendor->getId());
            $vendorToUpdate->setData($vendor->getData());
            return $this->vendorResource->save($vendorToUpdate);
        }

        return $this->vendorResource->save($vendor);
    }

    /**
     * @param VendorEntity $vendor
     * @return bool
     * @throws \Exception
     */
    public function delete(VendorEntity $vendor): bool
    {
        /** @var AbstractEntity|VendorResource $vendorResource */
        $this->vendorResource->delete($vendor);

        return $vendor->isDeleted();
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteById(int $id): bool
    {
        /** @var VendorEntity|AbstractExtensibleModel $vendorDto */
        $vendorDto = $this->vendorFactory->create();
        $this->vendorResource->load($vendorDto, $id);

        return $this->delete($vendorDto);
    }
}
