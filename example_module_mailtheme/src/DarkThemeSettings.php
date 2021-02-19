<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
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
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\Module\ExampleModuleMailtheme;

use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Class DarkThemeSettings is responsible of accessing/saving the theme settings in PrestaShop configuration
 */
class DarkThemeSettings
{
    const SETTINGS_KEY = 'EXAMPLE_MODULE_MAILTHEME_DARK_THEME_SETTINGS';
    const DEFAULT_CUSTOM_MESSAGE = 'My custom message';
    const CUSTOM_MESSAGE_MAX_SIZE = 512;

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
