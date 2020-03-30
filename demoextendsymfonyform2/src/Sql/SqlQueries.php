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

namespace PrestaShop\Module\DemoExtendSymfonyForm\Sql;

/**
 * Class SqlQueries
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Sql
 */
class SqlQueries
{
    /**
     * Install database queries.
     *
     * @return array
     */
    public static function installQueries(): array
    {
        return [
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'supplier_extra_image` (
              `id_extra_image` int(11) NOT NULL AUTO_INCREMENT,
              `id_supplier` int(11) NOT NULL,
              `image_name` varchar(64) NOT NULL,
              PRIMARY KEY (`id_extra_image`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        ];
    }

    /**
     * Uninstall database queries.
     *
     * @return bool
     */
    public static function uninstallQueries(): array
    {
        return [
            'DROP TABLE IF EXISTS `'._DB_PREFIX_.'supplier_extra_image`',
        ];
    }
}
