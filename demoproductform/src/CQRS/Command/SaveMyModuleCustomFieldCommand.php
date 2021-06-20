<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace PrestaShop\Module\DemoProductForm\CQRS\Command;

use PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder\ModuleProductCommandsBuilder;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;

/**
 * Product form is quite big so we have multiple command handlers that saves the fields and performs other required actions
 * This means you can also add your command handler to handle some custom fields added by your module.
 * To do that you will need to create your commandsBuilder which will build commands from product form data
 *
 * @see ModuleProductCommandsBuilder
 *
 * It is example command, you can call it whatever you need depending on use case.
 * Command is used to pass the information and call related handler, it doesnt actually do anything by itself.
 * The name of command should reflect the actual use case and should be handled by a handler
 *
 * @see SaveMyModuleCustomFieldHandler
 */
final class SaveMyModuleCustomFieldCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $value;

    /**
     * @param int $productId
     * @param string $value
     */
    public function __construct(int $productId, string $value)
    {
        $this->productId = new ProductId($productId);
        $this->value = $value;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
