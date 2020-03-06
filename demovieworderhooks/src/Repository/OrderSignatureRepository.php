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

namespace PrestaShop\Module\DemoViewOrderHooks\Repository;

use Doctrine\ORM\EntityRepository;

class OrderSignatureRepository extends EntityRepository
{
    /**
     * @param int $orderId
     *
     * @return object|null
     */
    public function findOneByOrderId(int $orderId)
    {
        return $this->findOneBy(['orderId' => $orderId]);
    }
}

