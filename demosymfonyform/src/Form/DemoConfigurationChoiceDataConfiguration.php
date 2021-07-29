<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyForm\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Handles configuration data for tax options.
 */
final class DemoConfigurationChoiceDataConfiguration implements DataConfigurationInterface
{
    public const CATEGORY_CHOICE_TREE_TYPE = 'DEMO_SYMFONY_FORM_CATEGORY_CHOICE_TREE_TYPE';
    public const COUNTRY_CHOICE_TYPE = 'DEMO_SYMFONY_FORM_COUNTRY_CHOICE_TYPE';
    public const MATERIAL_CHOICE_TABLE_TYPE = 'DEMO_SYMFONY_FORM_MATERIAL_CHOICE_TABLE_TYPE';
    public const MATERIAL_CHOICE_TREE_TYPE = 'DEMO_SYMFONY_FORM_MATERIAL_CHOICE_TREE_TYPE';
    public const MATERIAL_CHOICE_MULTIPLE_CHOICES_TABLE = 'DEMO_SYMFONY_FORM_MATERIAL_CHOICE_MULTIPLE_CHOICES_TABLE';
    public const SHOP_CHOICES_TREE_TYPE = 'DEMO_SYMFONY_FORM_SHOP_CHOICES_TREE_TYPE';
    public const SWITCH_TYPE = 'DEMO_SYMFONY_FORM_SWITCH_TYPE';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];

        if ($categoryChoiceTreeType = $this->configuration->get(static::CATEGORY_CHOICE_TREE_TYPE)) {
            $return['category_choice_tree_type'] = $categoryChoiceTreeType;
        }
        if ($countryChoiceType = $this->configuration->get(static::COUNTRY_CHOICE_TYPE)) {
            $return['country_choice_type'] = $countryChoiceType;
        }
        if ($materialChoiceTableType = $this->configuration->get(static::MATERIAL_CHOICE_TABLE_TYPE)) {
            $return['material_choice_table_type'] = json_decode($materialChoiceTableType, true);
        }
        if ($materialChoiceTreeType = $this->configuration->get(static::MATERIAL_CHOICE_TREE_TYPE)) {
            $return['material_choice_tree_type'] = $materialChoiceTreeType;
        }
        if ($materialChoiceMultipleChoicesTable = $this->configuration->get(static::MATERIAL_CHOICE_MULTIPLE_CHOICES_TABLE)) {
            $return['material_choice_multiple_choices_table'] = json_decode($materialChoiceMultipleChoicesTable, true);
        }
        if ($shopChoicesTreeType = $this->configuration->get(static::SHOP_CHOICES_TREE_TYPE)) {
            $return['shop_choices_tree_type'] = json_decode($shopChoicesTreeType, true);
        }
        if ($switchType = $this->configuration->get(static::SWITCH_TYPE)) {
            $return['switch_type'] = $switchType;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $this->configuration->set(static::CATEGORY_CHOICE_TREE_TYPE, $configuration['category_choice_tree_type']);
        $this->configuration->set(static::COUNTRY_CHOICE_TYPE, $configuration['country_choice_type']);
        $this->configuration->set(static::MATERIAL_CHOICE_TABLE_TYPE, json_encode($configuration['material_choice_table_type']));
        $this->configuration->set(static::MATERIAL_CHOICE_TREE_TYPE, $configuration['material_choice_tree_type']);
        $this->configuration->set(static::MATERIAL_CHOICE_MULTIPLE_CHOICES_TABLE, json_encode($configuration['material_choice_multiple_choices_table']));
        $this->configuration->set(static::SHOP_CHOICES_TREE_TYPE, json_encode($configuration['shop_choices_tree_type']));
        $this->configuration->set(static::SWITCH_TYPE, $configuration['switch_type']);

        return [];
    }

    /**
     * Ensure the parameters passed are valid.
     * This function can be used to validate updateConfiguration(array $configuration) data input.
     *
     * @param array $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}
