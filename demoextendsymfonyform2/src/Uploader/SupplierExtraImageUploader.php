<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoExtendSymfonyForm\Uploader;

use PrestaShop\Module\DemoExtendSymfonyForm\Entity\SupplierExtraImage;
use PrestaShop\Module\DemoExtendSymfonyForm\Repository\SupplierExtraImageRepository;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageOptimizationException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageUploadException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\MemoryLimitException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\UploadedImageConstraintException;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SupplierExtraImageUploader
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Uploader
 */
class SupplierExtraImageUploader implements ImageUploaderInterface
{
    /** @var SupplierExtraImageRepository */
    private $supplierExtraImageRepository;

    /**
     * @param SupplierExtraImageRepository $supplierExtraImageRepository
     */
    public function __construct(SupplierExtraImageRepository $supplierExtraImageRepository)
    {
        $this->supplierExtraImageRepository = $supplierExtraImageRepository;
    }

    /**
     * @param int $supplierId
     * @param UploadedFile $image
     */
    public function upload($supplierId, UploadedFile $image)
    {
        $this->checkImageIsAllowedForUpload($image);
        $tempImageName = $this->createTemporaryImage($image);
        $this->deleteOldImage($supplierId);

        $originalImageName = $image->getClientOriginalName();
        $destination = _PS_SUPP_IMG_DIR_ . $originalImageName;
        $this->uploadFromTemp($tempImageName, $destination);
        $this->supplierExtraImageRepository->upsertSupplierImageName($supplierId, $originalImageName);
    }

    /**
     * Creates temporary image from uploaded file
     *
     * @param UploadedFile $image
     *
     * @throws ImageUploadException
     *
     * @return string
     */
    protected function createTemporaryImage(UploadedFile $image)
    {
        $temporaryImageName = tempnam(_PS_TMP_IMG_DIR_, 'PS');

        if (!$temporaryImageName || !move_uploaded_file($image->getPathname(), $temporaryImageName)) {
            throw new ImageUploadException('Failed to create temporary image file');
        }

        return $temporaryImageName;
    }

    /**
     * Uploads resized image from temporary folder to image destination
     *
     * @param $temporaryImageName
     * @param $destination
     *
     * @throws ImageOptimizationException
     * @throws MemoryLimitException
     */
    protected function uploadFromTemp($temporaryImageName, $destination)
    {
        if (!\ImageManager::checkImageMemoryLimit($temporaryImageName)) {
            throw new MemoryLimitException('Cannot upload image due to memory restrictions');
        }

        if (!\ImageManager::resize($temporaryImageName, $destination)) {
            throw new ImageOptimizationException('An error occurred while uploading the image. Check your directory permissions.');
        }

        unlink($temporaryImageName);
    }

    /**
     * Deletes old image
     *
     * @param $supplierId
     */
    private function deleteOldImage($supplierId)
    {
        /** @var SupplierExtraImage $supplierExtraImage */
        $supplierExtraImage = $this->supplierExtraImageRepository->findOneBy(['supplierId' => $supplierId]);
        if ($supplierExtraImage && file_exists(_PS_SUPP_IMG_DIR_ . $supplierExtraImage->getImageName())) {
            unlink(_PS_SUPP_IMG_DIR_ . $supplierExtraImage->getImageName());
        }
    }

    /**
     * Check if image is allowed to be uploaded.
     *
     * @param UploadedFile $image
     *
     * @throws UploadedImageConstraintException
     */
    protected function checkImageIsAllowedForUpload(UploadedFile $image)
    {
        $maxFileSize = \Tools::getMaxUploadSize();

        if ($maxFileSize > 0 && $image->getSize() > $maxFileSize) {
            throw new UploadedImageConstraintException(sprintf('Max file size allowed is "%s" bytes. Uploaded image size is "%s".', $maxFileSize, $image->getSize()), UploadedImageConstraintException::EXCEEDED_SIZE);
        }

        if (!\ImageManager::isRealImage($image->getPathname(), $image->getClientMimeType())
            || !\ImageManager::isCorrectImageFileExt($image->getClientOriginalName())
            || preg_match('/\%00/', $image->getClientOriginalName()) // prevent null byte injection
        ) {
            throw new UploadedImageConstraintException(sprintf('Image format "%s", not recognized, allowed formats are: .gif, .jpg, .png', $image->getClientOriginalExtension()), UploadedImageConstraintException::UNRECOGNIZED_FORMAT);
        }
    }
}
