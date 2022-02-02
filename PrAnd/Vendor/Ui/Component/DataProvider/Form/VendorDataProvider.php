<?php

namespace PrAnd\Vendor\Ui\Component\DataProvider\Form;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Ui\DataProvider\AbstractDataProvider;

use PrAnd\Vendor\Api\Data\VendorInterface;
use PrAnd\Vendor\Api\Data\VendorCollectionInterface;
use PrAnd\Vendor\Api\VendorImageServiceInterface;
use PrAnd\Vendor\Model\ResourceModel\Vendor\CollectionFactory;

class VendorDataProvider extends AbstractDataProvider
{
    /**
     * @var VendorCollectionInterface|AbstractCollection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /** @var VendorImageServiceInterface  */
    protected $vendorImage;

    /**
     * VendorDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $vendorCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param VendorImageServiceInterface $vendorImage
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $vendorCollectionFactory,
        DataPersistorInterface $dataPersistor,
        VendorImageServiceInterface $vendorImage,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $vendorCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->vendorImage = $vendorImage;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $this->collection->addAttributeToSelect('*');
        $items = $this->collection->getItems();

        /** @var VendorInterface|AbstractExtensibleModel $item */
        foreach ($items as $item) {
            $this->loadedData[$item->getId()]['vendor'] = $item->getData();
            $preparedImageData = $this->vendorImage->getPreparedData($item->getImage());

            if (!empty($preparedImageData)) {
                $this->loadedData[$item->getId()]['vendor']['image'] = [$preparedImageData];
            }
        }

        /** @var array $persistorVendor */
        $persistorVendor = $this->dataPersistor->get('vendor');

        if (!empty($persistorVendor)) {
            /** @var VendorInterface|AbstractExtensibleModel $articleDTO */
            $articleDTO = $this->collection->getNewEmptyItem();
            $articleDTO->setData($persistorVendor);

            $this->loadedData[$articleDTO->getId()]['vendor'] = $articleDTO->getData();
            $this->dataPersistor->clear('vendor');
        }

        return $this->loadedData;
    }
}
