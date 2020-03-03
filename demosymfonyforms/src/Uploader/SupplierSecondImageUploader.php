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

namespace PrestaShop\Module\DemoSymfonyForms\Uploader;

use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageOptimizationException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageUploadException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\MemoryLimitException;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SupplierSecondImageUploader implements ImageUploaderInterface
{
    const EXTRA_IMAGE_NAME = 'extra';

    public function upload($supplierId, UploadedFile $image)
    {
        $tempImageName = $this->createTemporaryImage($image);
        $this->deleteOldImage($supplierId);

        $destination = _PS_SUPP_IMG_DIR_. self::EXTRA_IMAGE_NAME . $supplierId . '.jpg';
        $this->uploadFromTemp($tempImageName, $destination);
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
        $currentImage =  _PS_SUPP_IMG_DIR_ . self::EXTRA_IMAGE_NAME . $supplierId . '.jpg';

        if (file_exists($currentImage)) {
            unlink($currentImage);
        }
    }
}
