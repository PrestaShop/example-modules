<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Repository;

use Doctrine\DBAL\Connection;
use PDO;

class ReviewerRepository
{
    private Connection $connection;

    private string $dbPrefix;

    public function __construct(Connection $connection, string $dbPrefix)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * Finds customer id if such exists.
     */
    public function findIdByCustomer(int $customerId): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('`id_reviewer`')
            ->from($this->dbPrefix . 'democqrshooksusage_reviewer')
            ->where('`id_customer` = :customer_id')
        ;

        $queryBuilder->setParameter('customer_id', $customerId);

        return (int) $queryBuilder->execute()->fetchOne(PDO::FETCH_COLUMN);
    }

    /**
     * Gets allowed to review status by customer.
     */
    public function getIsAllowedToReviewStatus(int $customerId): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('`is_allowed_for_review`')
            ->from($this->dbPrefix . 'democqrshooksusage_reviewer')
            ->where('`id_customer` = :customer_id')
        ;

        $queryBuilder->setParameter('customer_id', $customerId);

        return (bool) $queryBuilder->execute()->fetchOne(PDO::FETCH_COLUMN);
    }
}
