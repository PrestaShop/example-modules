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

namespace PrestaShop\Module\DemoExtendSymfonyForm\Controller;

use PrestaShop\Module\DemoExtendSymfonyForm\Entity\SupplierExtraImage;
use PrestaShop\Module\DemoExtendSymfonyForm\Repository\SupplierExtraImageRepository;
use PrestaShop\PrestaShop\Core\Domain\Category\Exception\CannotDeleteImageException;
use PrestaShop\PrestaShop\Core\Domain\Supplier\Exception\SupplierException;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DemoSupplierController
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Controller
 */
class DemoSupplierController extends FrameworkBundleAdminController
{

    /**
     * Deletes image.
     *
     * @param int $supplierId
     *
     * @return RedirectResponse
     */
    public function deleteExtraImageAction(int $supplierId)
    {
        try {
            $this->deleteExtraUploadedImage($supplierId);

            $this->addFlash(
                'success',
                $this->trans('The image was successfully deleted.', 'Admin.Notifications.Success')
            );
        } catch (SupplierException $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages()));
        }

        return $this->redirectToRoute('admin_suppliers_edit', [
            'supplierId' => $supplierId,
        ]);
    }

    /**
     * Provides error messages for exceptions
     * @return array
     */
    private function getErrorMessages()
    {
        return [
            CannotDeleteImageException::class => $this->trans(
                'Second supplier image could not be deleted!',
                'Admin.Notifications.Error'
            ),
        ];
    }

    /**
     * @param int $supplierId
     *
     * @return bool
     * @throws CannotDeleteImageException
     */
    private function deleteExtraUploadedImage(int $supplierId)
    {
        /** @var SupplierExtraImageRepository $supplierExtraImageRepository */
        $supplierExtraImageRepository = $this->get(
            'prestashop.module.demoextendsymfonyform.repository.supplier_extra_image_repository'
        );
        /** @var SupplierExtraImage $supplierExtraImage */
        $supplierExtraImage = $supplierExtraImageRepository->findOneBy(['supplierId' => $supplierId]);
        if ($supplierExtraImage) {
            $extraImageName = $supplierExtraImage->getImageName();
            $supplierExtraImageRepository->deleteExtraImage($supplierExtraImage);
            // check if the same image was associated with other suppliers
            /** @var SupplierExtraImage $supplierExtraImage */
            $supplierExtraImage = $supplierExtraImageRepository->findOneBy(['imageName' => $extraImageName]);
            $imgPath = _PS_SUPP_IMG_DIR_ . $extraImageName;
            if ($supplierExtraImage === null && file_exists($imgPath) && unlink($imgPath)) {
                return true;
            }
        }

        throw new CannotDeleteImageException(sprintf(
            'Cannot delete extra image for supplier with id "%s"',
            $supplierId
        ));
    }
}
