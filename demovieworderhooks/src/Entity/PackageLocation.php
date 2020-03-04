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
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id_package_location", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_order", type="integer")
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string")
     */
    private $location;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return PackageLocation
     */
    public function setId(?int $id): PackageLocation
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     *
     * @return PackageLocation
     */
    public function setOrderId(int $orderId): PackageLocation
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     *
     * @return PackageLocation
     */
    public function setLocation(string $location): PackageLocation
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return PackageLocation
     */
    public function setPosition(int $position): PackageLocation
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     *
     * @return PackageLocation
     */
    public function setDate(?DateTime $date): PackageLocation
    {
        $this->date = $date;

        return $this;
    }
}
