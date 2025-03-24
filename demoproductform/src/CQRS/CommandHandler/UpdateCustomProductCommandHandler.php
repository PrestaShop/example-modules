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

namespace PrestaShop\Module\DemoProductForm\CQRS\CommandHandler;

use PrestaShop\Module\DemoProductForm\CQRS\Command\UpdateCustomProductCommand;
use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\PrestaShop\Core\CommandBus\Attributes\AsCommandHandler;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\ProductFormDataHandler;

/**
 * Handles @see UpdateCustomProductCommand
 */
#[AsCommandHandler]
final class UpdateCustomProductCommandHandler
{
    /**
     * This method will be triggered when related command is dispatched
     * (more about cqrs https://devdocs.prestashop.com/8/development/architecture/domain/cqrs/)
     *
     * Note - product form data handler create() method is a little unique
     *
     * @param UpdateCustomProductCommand $command
     *
     * @see ProductFormDataHandler::create()
     *
     * It will create the product with couple required fields and then call the update method,
     * so you don't actually need to hook on ProductFormDataHandler::create() method
     */
    public function handle(UpdateCustomProductCommand $command): void
    {
        // Command handlers should contain as less logic as possible, that should be wrapped in dedicated services instead,
        // but for simplicity of example lets just leave the entity saving logic here
        $productId = $command->getProductId()->getValue();
        $customProduct = new CustomProduct($productId);

        $updatedFields = [];
        if (null !== $command->getCustomerField()) {
            $customProduct->custom_field = $command->getCustomerField();
            $updatedFields['custom_field'] = true;
        }

        if (null !== $command->getCustomPrice()) {
            $customProduct->custom_price = (float) (string) $command->getCustomPrice();
            $updatedFields['custom_price'] = true;
        }

        if (empty($customProduct->id)) {
            // If custom is not found it has not been created yet, so we force its ID to match the product ID
            $customProduct->id = $productId;
            $customProduct->force_id = true;
            $customProduct->add();
        } else {
            // setFieldsToUpdate can be set to explicitly specify fields for update (other fields would not be updated)
            $customProduct->setFieldsToUpdate($updatedFields);
            $customProduct->update();
        }
    }
}
