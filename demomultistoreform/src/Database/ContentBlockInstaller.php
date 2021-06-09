<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;

class ContentBlockInstaller
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     */
    public function __construct(Connection $connection, string $dbPrefix)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * For now, we cannot use our module's doctrine entities during the installation's process, because our
     * new entities are not recognized by Doctrine yet.
     * This is why we execute an sql query to create our tables.
     *
     * @return bool
     */
    public function createTables(): bool
    {
        $this->dropTables();
        $sqlFile = __DIR__ . '/../../Resources/install.sql';
        $sqlQueries = explode(PHP_EOL, file_get_contents($sqlFile));
        $sqlQueries = str_replace('PREFIX_', $this->dbPrefix, $sqlQueries);

        foreach ($sqlQueries as $query) {
            if (empty($query)) {
                continue;
            }
            $statement = $this->connection->executeQuery($query);
            if (0 != (int) $statement->errorCode()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function dropTables(): bool
    {
        $tableNames = [
            'content_block_shop',
            'content_block',
        ];
        foreach ($tableNames as $tableName) {
            $sql = 'DROP TABLE IF EXISTS ' . $this->dbPrefix . $tableName;
            $statement = $this->connection->executeQuery($sql);
            if ($statement instanceof Statement && 0 != (int) $statement->errorCode()) {
                return false;
            }
        }

        return true;
    }
}
