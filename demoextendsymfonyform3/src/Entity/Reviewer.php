<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Entity;

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;

/**
 * This entity database state is managed by PrestaShop ObjectModel
 */
class Reviewer extends ObjectModel
{
    public int $id_customer;

    public int $is_allowed_for_review;

    public static $definition = [
        'table' => 'democqrshooksusage_reviewer',
        'primary' => 'id_reviewer',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'is_allowed_for_review' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
        ],
    ];
}
