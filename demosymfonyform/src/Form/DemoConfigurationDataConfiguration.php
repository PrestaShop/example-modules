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
final class DemoConfigurationDataConfiguration implements DataConfigurationInterface
{
    public const TRANSLATABLE_SIMPLE = 'DEMO_SYMFONY_FORM_TRANSLATABLE_SIMPLE_TYPE';
    public const TRANSLATABLE_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_TEXT_AREA_TYPE';
    public const TRANSLATABLE_FORMATTED_TEXT_AREA = 'DEMO_SYMFONY_FORM_TRANSLATABLE_FORMATTED_TEXT_AREA_TYPE';

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
        if ($translatableSimple = $this->configuration->get(self::TRANSLATABLE_SIMPLE)) {
            $return['translatable_type'] = json_decode($translatableSimple, true);
        }

        if ($translatableSimple = $this->configuration->get(self::TRANSLATABLE_TEXT_AREA)) {
            $return['translatable_text_area_type'] = json_decode($translatableSimple, true);
        }

        if ($translatableSimple = $this->configuration->get(self::TRANSLATABLE_FORMATTED_TEXT_AREA)) {
            $return['translatable_formatted_text_area_type'] = json_decode($translatableSimple, true);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $this->configuration->set(self::TRANSLATABLE_SIMPLE, json_encode($configuration['translatable_type']));
        $this->configuration->set(self::TRANSLATABLE_TEXT_AREA, json_encode($configuration['translatable_text_area_type']));
        $this->configuration->set(self::TRANSLATABLE_FORMATTED_TEXT_AREA, json_encode($configuration['translatable_formatted_text_area_type'], JSON_UNESCAPED_SLASHES), null, ['html' => true]);

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
