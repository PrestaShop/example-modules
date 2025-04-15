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

declare(strict_types=1);

namespace PrestaShop\Module\ApiModule\ApiPlatform\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProviderInterface;
use PrestaShop\Module\ApiModule\ApiPlatform\Resources\Horse;
use PrestaShop\Module\ApiModule\Repository\HorseRepository;

/**
 * @implements ProviderInterface<Horse|null>
 */
final class HorseProvider implements ProviderInterface
{
    public function __construct(
        private readonly HorseRepository $horseRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|Horse|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->findAllHorses();
        }

        $horse = $this->findHorseById($uriVariables['id']);

        if ($operation instanceof Put || $operation instanceof Patch) {
            // We need to provide new data to the processor!
            $newData = json_decode($context['request']->getContent());

            if (null !== $newData->name) {
                $horse->name = $newData->name;
            }

            if (null !== $newData->color) {
                $horse->color = $newData->color;
            }

            if (null !== $newData->weight) {
                $horse->weight = $newData->weight;
            }
        }

        return $horse;
    }

    private function findHorseById(int $id): ?Horse
    {
        $omHorse = $this->horseRepository->findHorseById($id);

        if (!$omHorse) {
            return null;
        }

        return Horse::fromObjectModel($omHorse);
    }

    private function findAllHorses(): array
    {
        $horses = $this->horseRepository->findAllHorses();

        $horsesArray = [];

        foreach ($horses as $horse) {
            $h = new Horse();
            $h->id = (int) $horse['id_horse'];
            $h->name = $horse['name'];
            $h->color = $horse['color'];
            $h->weight = $horse['weight'];

            $horsesArray[] = $h;
        }

        return $horsesArray;
    }
}
