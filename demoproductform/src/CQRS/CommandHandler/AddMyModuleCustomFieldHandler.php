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

final class AddMyModuleCustomFieldHandler
{
    /**
     * @param AddMyModuleCustomFieldCommand $command
     */
    public function handle(AddMyModuleCustomFieldCommand $command): void
    {
        // perform actions to add the field to database.
    }
}
