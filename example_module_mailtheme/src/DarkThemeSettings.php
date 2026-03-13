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

namespace PrestaShop\Module\ExampleModuleMailtheme;

use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Class DarkThemeSettings is responsible for accessing/saving the theme settings in PrestaShop configuration
 */
class DarkThemeSettings
{
    public const SETTINGS_KEY = 'EXAMPLE_MODULE_MAILTHEME_DARK_THEME_SETTINGS';
    public const DEFAULT_CUSTOM_MESSAGE = 'My custom message';
    public const CUSTOM_MESSAGE_MAX_SIZE = 512;

    /** @var ConfigurationInterface */
    private $configuration;

    /**
     * @param array $languages
     */
    private $languages;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(
        ConfigurationInterface $configuration,
        array $languages
    ) {
        $this->configuration = $configuration;
        $this->languages = $languages;
    }

    /**
     * @return array
     */
    public function getDefaultSettings()
    {
        $customMessage = [];
        foreach ($this->languages as $language) {
            $customMessage[$language['id_lang']] = self::DEFAULT_CUSTOM_MESSAGE;
        }

        return [
            'custom_message' => $customMessage,
            'primary_background_color' => '#222222',
            'secondary_background_color' => '#dddddd',
            'primary_text_color' => '#ffffff',
            'secondary_text_color' => '#25B9D7',
        ];
    }

    public function initSettings()
    {
        $this->saveSettings($this->getDefaultSettings());
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        $configurationSettings = $this->configuration->get(self::SETTINGS_KEY);
        if (empty($configurationSettings)) {
            return $this->getDefaultSettings();
        }

        $settings = json_decode($configurationSettings, true);

        return empty($settings) ? $this->getDefaultSettings() : $settings;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getCustomMessageByLocale($locale)
    {
        $settings = $this->getSettings();
        foreach ($this->languages as $language) {
            if ($locale === $language['locale'] && !empty($settings['custom_message'][$language['id_lang']])) {
                return $settings['custom_message'][$language['id_lang']];
            }
        }

        return self::DEFAULT_CUSTOM_MESSAGE;
    }

    /**
     * @param array $settings
     */
    public function saveSettings(array $settings)
    {
        $this->configuration->set(self::SETTINGS_KEY, json_encode($settings));
    }
}
