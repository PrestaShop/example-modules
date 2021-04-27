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

use PrestaShop\Module\DemoProductForm\CQRS\Command\AddMyModuleCustomFieldCommand;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Handles @see AddMyModuleCustomFieldCommand
 */
final class AddMyModuleCustomFieldHandler
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
     * @param AddMyModuleCustomFieldCommand $command
     */
    public function handle(AddMyModuleCustomFieldCommand $command): void
    {
        // do what you need with your command here. For example we are saving it to configuration
        $this->configuration->set('DEMOPRODUCTFORM_CUSTOM_FIELD', $command->getValue());
    }
}
