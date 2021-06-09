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

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class ContentBlockConfigurationFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $contentBlockConfigurationDataConfiguration;

    /**
     * @param DataConfigurationInterface $contentBlockConfigurationDataConfiguration
     */
    public function __construct(DataConfigurationInterface $contentBlockConfigurationDataConfiguration)
    {
        $this->contentBlockConfigurationDataConfiguration = $contentBlockConfigurationDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->contentBlockConfigurationDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->contentBlockConfigurationDataConfiguration->updateConfiguration($data);
    }
}
