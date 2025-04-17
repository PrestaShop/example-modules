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

namespace PrestaShop\Module\ApiModule\Repository;

use Doctrine\DBAL\Connection;
use PrestaShop\Module\ApiModule\Entity\Horse;

class HorseRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $dbPrefix
    ) {
    }

    public function findHorseById(int $id): ?Horse
    {
        $horse = new Horse($id);

        if (!$horse->id) {
            return null;
        }

        return $horse;
    }

    public function findAllHorses(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('h.id_horse, h.name, h.color, h.weight')
            ->from($this->dbPrefix . 'horse', 'h')
            ->executeQuery()
            ->fetchAllAssociative()
        ;
    }

    public function create(string $name, string $color, float $weight): Horse
    {
        $horse = new Horse();
        $horse->name = $name;
        $horse->color = $color;
        $horse->weight = $weight;
        $horse->save();

        return $horse;
    }

    public function delete(int $id): void
    {
        $horse = new Horse($id);
        $horse->delete();
    }

    public function update(int $id, string $name, string $color, float $weight): Horse
    {
        $horse = new Horse($id);
        $horse->name = $name;
        $horse->color = $color;
        $horse->weight = $weight;
        $horse->save();

        return $horse;
    }
}
