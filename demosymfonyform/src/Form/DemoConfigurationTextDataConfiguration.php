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

namespace PrestaShop\Module\DemoSymfonyForm\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use PrestaShop\PrestaShop\Core\Tax\Ecotax\ProductEcotaxResetterInterface;

/**
 * Handles configuration data for tax options.
 */
final class DemoConfigurationTextDataConfiguration implements DataConfigurationInterface
{
    public const TRANSLATABLE_SIMPLE = 'DEMO_SYMFONY_FORM_TRANSLATABLE_SIMPLE_TYPE';
    public const TRANSLATABLE_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_TEXT_AREA_TYPE';
    public const TRANSLATABLE_FORMATTED_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_FORMATTED_TEXT_AREA_TYPE';
    public const FORMATTED_TEXT_AREA_TYPE = 'DEMO_SYMFONY_FORM_FORMATTED_TEXT_AREA_TYPE';
    public const GENERATABLE_TEXT_TYPE = 'DEMO_SYMFONY_FORM_GENERATABLE_TEXT_TYPE';
    public const TEXT_WITH_LENGTH_COUNTER_TYPE = 'DEMO_SYMFONY_FORM_TEXT_WITH_LENGTH_COUNTER_TYPE';
    public const TEXT_WITH_UNIT_TYPE = 'DEMO_SYMFONY_FORM_TEXT_WITH_UNIT_TYPE';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];
        if ($translatableSimple = $this->configuration->get(static::TRANSLATABLE_SIMPLE)) {
            $return['translatable_type'] = $translatableSimple;
        }

        if ($translatableTextArea = $this->configuration->get(static::TRANSLATABLE_TEXT_AREA)) {
            $return['translatable_text_area_type'] = $translatableTextArea;
        }

        if ($translatableFormattedTextArea = $this->configuration->get(static::TRANSLATABLE_FORMATTED_TEXT_AREA)) {
            $return['translatable_formatted_text_area_type'] = $translatableFormattedTextArea;
        }

        if ($formattedTextAreaType = $this->configuration->get(static::FORMATTED_TEXT_AREA_TYPE)) {
            $return['formatted_text_area_type'] = $formattedTextAreaType;
        }
        if ($generatableTextType = $this->configuration->get(static::GENERATABLE_TEXT_TYPE)) {
            $return['generatable_text_type'] = $generatableTextType;
        }
        if ($textWithLengthCounterType = $this->configuration->get(static::TEXT_WITH_LENGTH_COUNTER_TYPE)) {
            $return['text_with_length_counter_type'] = $textWithLengthCounterType;
        }
        if ($textWithUnitType = $this->configuration->get(static::TEXT_WITH_UNIT_TYPE)) {
            $return['text_with_unit_type'] = $textWithUnitType;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $this->configuration->set(static::TRANSLATABLE_SIMPLE, $configuration['translatable_type']);
        $this->configuration->set(static::TRANSLATABLE_TEXT_AREA, $configuration['translatable_text_area_type']);
        $this->configuration->set(static::TRANSLATABLE_FORMATTED_TEXT_AREA, $configuration['translatable_formatted_text_area_type'], null, ['html' => true]);
        $this->configuration->set(static::FORMATTED_TEXT_AREA_TYPE, $configuration['formatted_text_area_type']);
        $this->configuration->set(static::GENERATABLE_TEXT_TYPE, $configuration['generatable_text_type']);
        $this->configuration->set(static::TEXT_WITH_LENGTH_COUNTER_TYPE, $configuration['text_with_length_counter_type']);
        $this->configuration->set(static::TEXT_WITH_UNIT_TYPE, $configuration['text_with_unit_type']);

        return [];
    }

    /**
     * Ensure the parameters passed are valid.
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
