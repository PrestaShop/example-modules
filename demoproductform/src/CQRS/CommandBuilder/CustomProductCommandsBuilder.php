<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder;

use PrestaShop\Module\DemoProductForm\CQRS\Command\UpdateCustomProductCommand;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\Product\ProductCommandsBuilderInterface;

/**
 * This class is responsible for building cqrs commands from product form data.
 * Once you tag this service as "core.product_command_builder" (check module services.yml)
 * the core ProductCommandsBuilder will start using this builder
 *
 * Don't forget you can also handle your custom fields like any other identifiable object
 * by using following product form hooks instead of CQRS commands builder:
 *  - actionAfterUpdateProductFormHandler
 *  - actionBeforeUpdateProductFormHandler
 */
final class CustomProductCommandsBuilder implements ProductCommandsBuilderInterface
{
    public function buildCommands(ProductId $productId, array $formData, ShopConstraint $singleShopConstraint): array
    {
        $command = null;
        if (isset($formData['description']['demo_module_custom_field'])) {
            $command = $this->getCommand($command, $productId->getValue())->setCustomerField($formData['description']['demo_module_custom_field']);
        }
        if (isset($formData['custom_tab']['custom_price'])) {
            $command = $this->getCommand($command, $productId->getValue())->setCustomPrice((string) $formData['custom_tab']['custom_price']);
        }

        return null !== $command ? [$command] : [];
    }

    private function getCommand(?UpdateCustomProductCommand $existingCommand, int $productId): UpdateCustomProductCommand
    {
        if (null !== $existingCommand) {
            return $existingCommand;
        }

        return new UpdateCustomProductCommand($productId);
    }
}
