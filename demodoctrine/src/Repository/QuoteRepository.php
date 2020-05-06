<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\DemoDoctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
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
            array_splice($ids, 0, $limit);
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