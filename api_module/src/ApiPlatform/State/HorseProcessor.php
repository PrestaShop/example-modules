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

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use PrestaShop\Module\ApiModule\ApiPlatform\Resources\Horse;
use PrestaShop\Module\ApiModule\Repository\HorseRepository;

/**
 * @implements ProcessorInterface<Horse, Horse|void>
 */
final class HorseProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly HorseRepository $horseRepository,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof Post && $data instanceof Horse) {
            $horse = $this->horseRepository->create(
                $data->name,
                $data->color,
                $data->weight,
            );

            return Horse::fromObjectModel($horse);
        }

        if ($operation instanceof Delete && $data instanceof Horse) {
            $this->horseRepository->delete($data->id);

            return true;
        }

        if (($operation instanceof Put || $operation instanceof Patch) && $data instanceof Horse) {
            $horse = $this->horseRepository->update(
                $data->id,
                $data->name,
                $data->color,
                $data->weight,
            );

            return Horse::fromObjectModel($horse);
        }
    }
}
