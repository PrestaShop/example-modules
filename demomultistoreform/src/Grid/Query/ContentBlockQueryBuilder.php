<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Grid\Query;

use Doctrine\DBAL\Connection;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Context\ShopContext;

final class ContentBlockQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * ContentBlockQueryBuilder constructor.
     */
    public function __construct(
        Connection $connection,
        string $dbPrefix,
        private ShopContext $shopContext
    ) {
        parent::__construct($connection, $dbPrefix);
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('cb.id_content_block, cb.title, cb.description, cb.enable');

        if (!$this->shopContext->isAllShopContext()) {
            $qb->join('cb', $this->dbPrefix . 'content_block_shop', 'cbs', 'cbs.id_content_block = cb.id_content_block')
                ->where('cbs.id_shop in (' . implode(', ', $this->shopContext->getAssociatedShopIds()) . ')')
                ->groupBy('cb.id_content_block');
        }

        $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
        )
            ->setFirstResult($searchCriteria->getOffset() ?? 0)
            ->setMaxResults($searchCriteria->getLimit());

        $qb->orderBy('id_content_block');

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria): QueryBuilder
    {
        $qb = $this->getBaseQuery();
        $qb->select('COUNT(DISTINCT cb.id_content_block)');
        if (!$this->shopContext->isAllShopContext()) {
            $qb->join('cb', $this->dbPrefix . 'content_block_shop', 'cbs', 'cbs.id_content_block = cb.id_content_block')
                ->where('cbs.id_shop in (' . implode(', ', $this->shopContext->getAssociatedShopIds()) . ')');
        }

        return $qb;
    }

    private function getBaseQuery(): QueryBuilder
    {
        return $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix.'content_block', 'cb');
    }
}
