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

namespace PrestaShop\Module\DemoViewOrderHooks\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\DemoViewOrderHooks\Repository\PackageLocationRepository")
 */
class PackageLocation
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_package_location", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="id_order", type="integer")
     */
    private int $orderId;

    /**
     * @ORM\Column(name="location", type="string")
     */
    private string $location;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    private int $position;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private ?DateTime $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): PackageLocation
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): PackageLocation
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): PackageLocation
    {
        $this->location = $location;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): PackageLocation
    {
        $this->position = $position;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): PackageLocation
    {
        $this->date = $date;

        return $this;
    }
}
