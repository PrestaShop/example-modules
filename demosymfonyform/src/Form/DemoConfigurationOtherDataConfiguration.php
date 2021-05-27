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
final class DemoConfigurationOtherDataConfiguration implements DataConfigurationInterface
{
    public const CHANGE_PASSWORD_TYPE = 'DEMO_SYMFONY_FORM_CHANGE_PASSWORD_TYPE';
    public const MONEY_WITH_SUFFIX_TYPE = 'DEMO_SYMFONY_FORM_MONEY_WITH_SUFFIX_TYPE';
    public const DATE_PICKER_TYPE = 'DEMO_SYMFONY_FORM_DATE_PICKER_TYPE';
    public const DATE_RANGE_TYPE = 'DEMO_SYMFONY_FORM_DATE_RANGE_TYPE';
    public const INTEGER_MIN_MAX_FILTER_TYPE = 'DEMO_SYMFONY_FORM_INTEGER_MIN_MAX_FILTER_TYPE';
    public const NUMBER_MIN_MAX_FILTER_TYPE = 'DEMO_SYMFONY_FORM_NUMBER_MIN_MAX_FILTER_TYPE';
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
        if ($changePasswordType = $this->configuration->get(static::CHANGE_PASSWORD_TYPE)) {
            $return['change_password_type'] = $changePasswordType;
        }
        if ($moneyWithSuffixType = $this->configuration->get(static::MONEY_WITH_SUFFIX_TYPE)) {
            $return['money_with_suffix_type'] = $moneyWithSuffixType;
        }
        if ($datePickerType = $this->configuration->get(static::DATE_PICKER_TYPE)) {
            $return['date_picker_type'] = $datePickerType;
        }
        if ($dateRangeType = $this->configuration->get(static::DATE_RANGE_TYPE)) {
            $return['date_range_type'] = json_decode($dateRangeType, true);
        }
        if ($integerMinMaxFilterType = $this->configuration->get(static::INTEGER_MIN_MAX_FILTER_TYPE)) {
            $return['integer_min_max_filter_type'] = json_decode($integerMinMaxFilterType, true);
        }
        if ($numberMinMaxFilterType = $this->configuration->get(static::NUMBER_MIN_MAX_FILTER_TYPE)) {
            $return['number_min_max_filter_type'] = json_decode($numberMinMaxFilterType, true);
        }
        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $this->configuration->set(static::CHANGE_PASSWORD_TYPE, $configuration['change_password_type']);
        $this->configuration->set(static::MONEY_WITH_SUFFIX_TYPE, $configuration['money_with_suffix_type']);
        $this->configuration->set(static::DATE_PICKER_TYPE, $configuration['date_picker_type']);
        $this->configuration->set(static::DATE_RANGE_TYPE, json_encode($configuration['date_range_type']));
        $this->configuration->set(static::INTEGER_MIN_MAX_FILTER_TYPE, json_encode($configuration['integer_min_max_filter_type']));
        $this->configuration->set(static::NUMBER_MIN_MAX_FILTER_TYPE, json_encode($configuration['number_min_max_filter_type']));
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
