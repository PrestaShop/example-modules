<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoMultistoreForm\Form;

use PrestaShop\PrestaShop\Core\Configuration\AbstractMultistoreConfiguration;

/**
 * Handles configuration data for demo multistore configuration options.
 */
final class ContentBlockDataConfiguration extends AbstractMultistoreConfiguration
{

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];

        $return['color'] = $this->configuration->get('PS_DEMO_MULTISTORE_COLOR');
        $return['italic'] = $this->configuration->get('PS_DEMO_MULTISTORE_ITALIC');
        $return['bold'] = $this->configuration->get('PS_DEMO_MULTISTORE_BOLD');

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $shopConstraint = $this->getShopConstraint();
        $this->updateConfigurationValue('PS_DEMO_MULTISTORE_COLOR', 'color', $configuration, $shopConstraint);
        $this->updateConfigurationValue('PS_DEMO_MULTISTORE_ITALIC', 'italic', $configuration, $shopConstraint);
        $this->updateConfigurationValue('PS_DEMO_MULTISTORE_BOLD', 'bold', $configuration, $shopConstraint);

        return [];
    }

    /**
     * @param array $configuration
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}
