<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShop\Module\ApiModule\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

class DBInstaller
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $dbPrefix
    ) {
    }

    public function createTables(): array
    {
        $errors = [];
        $this->dropTables();
        $sqlInstallFile = __DIR__ . '/../Resources/data/install.sql';
        $sqlQueries = preg_split('/\r\n|\r|\n/', file_get_contents($sqlInstallFile));
        $sqlQueries = str_replace('PREFIX_', $this->dbPrefix, $sqlQueries);

        foreach ($sqlQueries as $query) {
            if (empty($query)) {
                continue;
            }

            try {
                $this->connection->executeQuery($query);
            } catch (DBALException $e) {
                $errors[] = [
                    'key' => $e->getMessage(),
                    'parameters' => [],
                    'domain' => 'Admin.Modules.Notification',
                ];
            }
        }

        return $errors;
    }

    public function dropTables(): array
    {
        $errors = [];
        $tableNames = [
            'hamster',
        ];
        foreach ($tableNames as $tableName) {
            $sql = 'DROP TABLE IF EXISTS ' . $this->dbPrefix . $tableName;
            try {
                $this->connection->executeQuery($sql);
            } catch (DBALException $e) {
                $errors[] = [
                    'key' => $e->getMessage(),
                    'parameters' => [],
                    'domain' => 'Admin.Modules.Notification',
                ];
            }
        }

        return $errors;
    }
}
