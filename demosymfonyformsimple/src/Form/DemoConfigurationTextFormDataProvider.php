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

declare(strict_types=1);

namespace PrestaShop\Module\DemoSymfonyFormSimple\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Provider is responsible for providing form data, in this case, it is returned from the configuration component.
 *
 * Class DemoConfigurationTextFormDataProvider
 */
class DemoConfigurationTextFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $demoConfigurationTextDataConfiguration;

    public function __construct(DataConfigurationInterface $demoConfigurationTextDataConfiguration)
    {
        $this->demoConfigurationTextDataConfiguration = $demoConfigurationTextDataConfiguration;
    }

    public function getData(): array
    {
        return $this->demoConfigurationTextDataConfiguration->getConfiguration();
    }

    public function setData(array $data): array
    {
        return $this->demoConfigurationTextDataConfiguration->updateConfiguration($data);
    }
}
