<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace PrestaShop\Module\DemoHowToExtendSymfonyForm\Repository;

use Doctrine\DBAL\Connection;
use PDO;

class ReviewerRepository
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
    public function __construct(Connection $connection, $dbPrefix)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
    }

    /**
     * Finds customer id if such exists.
     *
     * @param int $customerId
     *
     * @return int
     */
    public function findIdByCustomer($customerId)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('`id_reviewer`')
            ->from($this->dbPrefix . 'demoextendsymfonyform_reviewer')
            ->where('`id_customer` = :customer_id')
        ;

        $queryBuilder->setParameter('customer_id', $customerId);

        return (int) $queryBuilder->execute()->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * Gets allowed to review status by customer.
     *
     * @param int $customerId
     *
     * @return bool
     */
    public function getIsAllowedToReviewStatus($customerId)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('`is_allowed_for_review`')
            ->from($this->dbPrefix . 'demoextendsymfonyform_reviewer')
            ->where('`id_customer` = :customer_id')
        ;

        $queryBuilder->setParameter('customer_id', $customerId);

        return (bool) $queryBuilder->execute()->fetch(PDO::FETCH_COLUMN);
    }
}
