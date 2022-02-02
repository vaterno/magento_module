<?php

namespace PrAnd\Vendor\Service;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\UrlInterface;

use PrAnd\Vendor\Api\VendorImageServiceInterface;

class VendorImageService implements VendorImageServiceInterface
{
    /** @var DirectoryList  */
    protected $directoryList;

    /** @var File  */
    protected $fileSystem;

    /** @var UrlInterface  */
    protected $urlBuilder;

    /**
     * Image constructor.
     * @param DirectoryList $directoryList
     * @param File $fileSystem
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        DirectoryList $directoryList,
        File $fileSystem,
        UrlInterface $urlBuilder
    )
    {
        $this->directoryList = $directoryList;
        $this->fileSystem = $fileSystem;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param string|null $imageName
     * @return string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFullPathToImage(?string $imageName = null): ?string
    {
        if (!empty($imageName)) {
            $pathToImage = $this->directoryList->getPath('media') . '/' . self::BASE_TMP_PATH . '/' . $imageName;
            $isFileExists = $this->fileSystem->isFile($pathToImage) && $this->fileSystem->isExists($pathToImage);

            if ($isFileExists) {
                return $pathToImage;
            }
        }
    }

    /**
     * @param string|null $imageName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getUrl(?string $imageName = null): string
    {
        $url = '';

        if (!empty($imageName)) {
            $isImageExists = (bool)$this->getFullPathToImage($imageName);

            if ($isImageExists) {
                $url = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . self::BASE_TMP_PATH . '/' . $imageName;
            }
        }

        return $url;
    }

    /**
     * @param string|null $imageName
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getPreparedData(?string $imageName = null): array
    {
        $result = [];

        if (!empty($imageName)) {
            $pathToImage = $this->getFullPathToImage($imageName);

            if (!empty($pathToImage)) {
                $result = [
                    'url' => $this->getUrl($imageName),
                    'name' => $imageName,
                    'path' => $pathToImage,
                    'size' => \filesize($pathToImage)
                ];
            }
        }

        return $result;
    }
}
