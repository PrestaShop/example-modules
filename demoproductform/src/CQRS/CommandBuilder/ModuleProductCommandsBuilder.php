<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder;

use PrestaShop\Module\DemoProductForm\CQRS\Command\SaveMyModuleCustomFieldCommand;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
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
final class ModuleProductCommandsBuilder implements ProductCommandsBuilderInterface
{
    public function buildCommands(ProductId $productId, array $formData): array
    {
        $commands = [];

        if (isset($formData['basic']['demo_module_custom_field'])) {
            $commands[] = new SaveMyModuleCustomFieldCommand($formData['basic']['demo_module_custom_field']);
        }

        return $commands;
    }
}
