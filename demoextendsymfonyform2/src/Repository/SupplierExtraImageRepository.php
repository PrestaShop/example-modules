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

namespace PrestaShop\Module\DemoExtendSymfonyForm\Repository;

use Doctrine\ORM\EntityRepository;
use PrestaShop\Module\DemoExtendSymfonyForm\Entity\SupplierExtraImage;

/**
 * Class SupplierExtraImageRepository
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Repository
 */
class SupplierExtraImageRepository extends EntityRepository
{
    /**
     * @param $supplierId
     * @param $imageName
     */
    public function upsertSupplierImageName($supplierId, $imageName)
    {
        /** @var SupplierExtraImage $supplierExtraImage */
        $supplierExtraImage = $this->findOneBy(['supplierId' => $supplierId]);
        if (!$supplierExtraImage) {
            $supplierExtraImage = new SupplierExtraImage();
            $supplierExtraImage->setSupplierId($supplierId);
        }
        $supplierExtraImage->setImageName($imageName);

        $em = $this->getEntityManager();
        $em->persist($supplierExtraImage);
        $em->flush();
    }

    /**
     * @param SupplierExtraImage $supplierExtraImage
     */
    public function deleteExtraImage(SupplierExtraImage $supplierExtraImage)
    {
        $em = $this->getEntityManager();
        if ($supplierExtraImage) {
            $em->remove($supplierExtraImage);
            $em->flush();
        }
    }
}
