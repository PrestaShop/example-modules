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

namespace PrestaShop\Module\DemoExtendSymfonyForm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\DemoExtendSymfonyForm\Repository\SupplierExtraImageRepository")
 */
class SupplierExtraImage
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_extra_image", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;


    /**
     * @ORM\Column(name="id_supplier", type="integer")
     */
    private int $supplierId;

    /**
     * @ORM\Column(type="string")
     */
    private string $imageName;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function setSupplierId(int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }
}
