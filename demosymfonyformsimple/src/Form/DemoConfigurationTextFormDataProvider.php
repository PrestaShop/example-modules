<?php
declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyFormSimple\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Provider ir responsible for providing form data, in this case it's as simple as using configuration to do that
 *
 * Class DemoConfigurationTextFormDataProvider
 */
class DemoConfigurationTextFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $demoConfigurationTextDataConfiguration;

    /**
     * @param DataConfigurationInterface $demoConfigurationTextDataConfiguration
     */
    public function __construct(DataConfigurationInterface $demoConfigurationTextDataConfiguration)
    {
        $this->demoConfigurationTextDataConfiguration = $demoConfigurationTextDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->demoConfigurationTextDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->demoConfigurationTextDataConfiguration->updateConfiguration($data);
    }
}