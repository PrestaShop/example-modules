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

namespace Module\DemoDoctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class QuoteRepository extends EntityRepository
{
    /**
     * Since RAND() is not available by default in Doctrine and we haven't an extension that
     * adds it we perform the random fetch and sorting programmatically in PHP.
     *
     * @param int $langId
     * @param int $limit
     *
     * @return array
     */
    public function getRandom($langId = 0, $limit = 0)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('q')
            ->addSelect('q')
            ->addSelect('ql')
            ->leftJoin('q.quoteLangs', 'ql')
        ;

        if (0 !== $langId) {
            $qb
                ->andWhere('ql.lang = :langId')
                ->setParameter('langId', $langId)
            ;
        }

        $ids = $this->getAllIds();
        shuffle($ids);
        if ($limit > 0) {
            $ids = array_slice($ids, 0, $limit);
        }
        $qb
            ->andWhere('q.id in (:ids)')
            ->setParameter('ids', $ids)
        ;

        $quotes = $qb->getQuery()->getResult();
        uasort($quotes, function($a, $b) use ($ids) {
            return array_search($a->getId(), $ids) - array_search($b->getId(), $ids);
        });

        return $quotes;
    }

    public function getAllIds()
    {
        /** @var QueryBuilder $qb */
        $qb = $this
            ->createQueryBuilder('q')
            ->select('q.id')
        ;

        $quotes = $qb->getQuery()->getScalarResult();

        return array_map(function($quote) {
            return $quote['id'];
        }, $quotes);
    }
}
