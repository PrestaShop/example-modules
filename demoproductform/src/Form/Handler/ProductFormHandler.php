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

namespace PrestaShop\Module\DemoProductForm\Form\Handler;

use PrestaShop\Module\DemoProductForm\CQRS\Command\AddMyModuleCustomFieldCommand;

/**
 * Alternative method of handling product form fields is shown via cqrs command builder example
 *
 * @see AddMyModuleCustomFieldCommand
 */
final class ProductFormHandler
{
    /**
     * @param array<string, mixed> $formData
     */
    public function handleUpdate(array &$formData): void
    {
        if (isset($formData['basic']['demo_module_custom_field'])) {
            //Do anything you need with your custom field value
        }
    }
}
