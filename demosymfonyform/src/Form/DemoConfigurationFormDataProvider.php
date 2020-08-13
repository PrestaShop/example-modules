<?php

namespace PrestaShop\Module\DemoSymfonyForm\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class DemoConfigurationFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $demoConfigurationDataConfiguration;

    /**
     * @param DataConfigurationInterface $demoConfigurationDataConfiguration
     */
    public function __construct(DataConfigurationInterface $demoConfigurationDataConfiguration)
    {
        $this->demoConfigurationDataConfiguration = $demoConfigurationDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->demoConfigurationDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->demoConfigurationDataConfiguration->updateConfiguration($data);
    }
}
