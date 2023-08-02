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
 * Configuration is used to save data to configuration table and retrieve from it
 */
final class DemoConfigurationTextDataConfiguration implements DataConfigurationInterface
{
    public const TRANSLATABLE_SIMPLE = 'DEMO_SYMFONY_FORM_TRANSLATABLE_SIMPLE_TYPE';
    public const TRANSLATABLE_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_TEXT_AREA_TYPE';
    public const TRANSLATABLE_FORMATTED_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_FORMATTED_TEXT_AREA_TYPE';
    public const FORMATTED_TEXT_AREA_TYPE = 'DEMO_SYMFONY_FORM_FORMATTED_TEXT_AREA_TYPE';
    public const GENERATABLE_TEXT_TYPE = 'DEMO_SYMFONY_FORM_GENERATABLE_TEXT_TYPE';
    public const TEXT_WITH_LENGTH_COUNTER_TYPE = 'DEMO_SYMFONY_FORM_TEXT_WITH_LENGTH_COUNTER_TYPE';
    public const NUMBER_TYPE_WITH_UNIT = 'DEMO_SYMFONY_FORM_NUMBER_TYPE_WITH_UNIT';
    public const GEOCOORDINATES_TYPE_LAT = 'DEMO_SYMFONY_FORM_GEOCOORDINATES_TYPE_LAT';
    public const GEOCOORDINATES_TYPE_LON = 'DEMO_SYMFONY_FORM_GEOCOORDINATES_TYPE_LON';

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
        if ($numberTypeWithUnit = $this->configuration->get(static::NUMBER_TYPE_WITH_UNIT)) {
            $return['number_type_with_unit'] = $numberTypeWithUnit;
        }
        if ($geoCoordinatesLat = $this->configuration->get(static::GEOCOORDINATES_TYPE_LAT)) {
            $return['latitude'] = $geoCoordinatesLat;
        }
        if ($geoCoordinatesLon = $this->configuration->get(static::GEOCOORDINATES_TYPE_LON)) {
            $return['longitude'] = $geoCoordinatesLon;
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
        /* Adding html => true allows for configuration->set to save HTML values */
        $this->configuration->set(static::TRANSLATABLE_FORMATTED_TEXT_AREA, $configuration['translatable_formatted_text_area_type'], null, ['html' => true]);
        $this->configuration->set(static::FORMATTED_TEXT_AREA_TYPE, $configuration['formatted_text_area_type'], null, ['html' => true]);
        $this->configuration->set(static::GENERATABLE_TEXT_TYPE, $configuration['generatable_text_type']);
        $this->configuration->set(static::TEXT_WITH_LENGTH_COUNTER_TYPE, $configuration['text_with_length_counter_type']);
        $this->configuration->set(static::NUMBER_TYPE_WITH_UNIT, $configuration['number_type_with_unit']);
        $this->configuration->set(static::GEOCOORDINATES_TYPE_LAT, $configuration['latitude']);
        $this->configuration->set(static::GEOCOORDINATES_TYPE_LON, $configuration['longitude']);

        /* Errors are returned here. */
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
