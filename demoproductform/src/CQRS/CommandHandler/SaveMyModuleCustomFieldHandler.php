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

namespace PrestaShop\Module\DemoProductForm\CQRS\CommandHandler;

use PrestaShop\Module\DemoProductForm\CQRS\Command\SaveMyModuleCustomFieldCommand;
use PrestaShop\Module\DemoProductForm\Entity\CustomProduct;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\ProductFormDataHandler;

/**
 * Handles @see SaveMyModuleCustomFieldCommand
 */
final class SaveMyModuleCustomFieldHandler
{
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(
        ConfigurationInterface $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * This method will be triggered when related command is dispatched
     * (more about cqrs https://devdocs.prestashop.com/1.7/development/architecture/domain/cqrs/)
     *
     * Note - product form data handler create() method is a little unique
     * @see ProductFormDataHandler::create()
     *
     * It will create the product with couple required fields and then call the update method,
     * so you don't actually need to hook on ProductFormDataHandler::create() method
     *
     * @param SaveMyModuleCustomFieldCommand $command
     */
    public function handle(SaveMyModuleCustomFieldCommand $command): void
    {
        // Command handlers should contain as less logic as possible, that should be wrapped in dedicated services instead,
        // but for simplicity of example lets just leave the entity saving logic here
        $productId = $command->getProductId()->getValue();

        $customProduct = new CustomProduct($productId);
        $customProduct->custom_field = $command->getValue();

        if ((int) $customProduct->id === $productId) {
            $customProduct->update();

            return;
        }

        $customProduct->id = $productId;
        $customProduct->add();

        return;
    }
}
