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

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use PrestaShop\Module\ApiModule\ApiPlatform\Resources\Cat;

/**
 * @implements ProcessorInterface<Cat, Cat|void>
 */
final class CatProcessor implements ProcessorInterface
{
    private string $jsonStorageFile;
    private array $data;

    public function __construct() {
        $this->jsonStorageFile = CatProvider::STORAGE_FILE;
        $jsonData = file_get_contents($this->jsonStorageFile);
        $this->data = json_decode($jsonData, true);
    }

    /**
     * @return Cat|void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof DeleteOperationInterface) {
            return $this->remove($data, $operation, $uriVariables, $context);
        }

        return $this->persist($data, $operation, $uriVariables, $context);
    }

    private function remove(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        foreach ($this->data as $key => $cat) {
            if ($cat['uuid'] === $data->getUuid()) {
                unset($this->data[$key]);
            }
        }

        // reindex array
        $this->data = array_values($this->data);
        file_put_contents($this->jsonStorageFile, json_encode($this->data, JSON_PRETTY_PRINT));

        return $data;
    }

    private function persist(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $foundKey = null;
        // check if the cat is not already in the file
        foreach ($this->data as $key => $cat) {
            if ($cat['uuid'] === $data->getUuid()) {
                $foundKey = $key;
            }
        }

        // if $foundKey is null, then we add a new cat
        // is not, we update the existing cat
        $this->data[$foundKey] = [
            'uuid' => $data->getUuid(),
            'name' => $data->getName(),
        ];

        // reindex array
        $this->data = array_values($this->data);
        file_put_contents($this->jsonStorageFile, json_encode($this->data, JSON_PRETTY_PRINT));

        return $data;
    }
}
