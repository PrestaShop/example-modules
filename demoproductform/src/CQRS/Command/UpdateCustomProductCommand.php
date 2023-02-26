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

namespace PrestaShop\Module\DemoProductForm\CQRS\Command;

use PrestaShop\Decimal\DecimalNumber;
use PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder\CustomProductCommandsBuilder;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;

/**
 * Product form is quite big so we have multiple command handlers that saves the fields and performs other required actions
 * This means you can also add your command handler to handle some custom fields added by your module.
 * To do that you will need to create your commandsBuilder which will build commands from product form data
 *
 * @see CustomProductCommandsBuilder
 *
 * It is example command, you can call it whatever you need depending on use case.
 * Command is used to pass the information and call related handler, it doesnt actually do anything by itself.
 * The name of command should reflect the actual use case and should be handled by a handler
 * @see UpdateCustomProductCommandHandler
 */
final class UpdateCustomProductCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $customerField = '';

    /**
     * @var DecimalNumber|null
     */
    private $customPrice;

    /**
     * @param int $productId
     */
    public function __construct(int $productId)
    {
        $this->productId = new ProductId($productId);
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
    public function getCustomerField(): string
    {
        return $this->customerField;
    }

    /**
     * @param string $customerField
     */
    public function setCustomerField(string $customerField): self
    {
        $this->customerField = $customerField;

        return $this;
    }

    /**
     * @return DecimalNumber|null
     */
    public function getCustomPrice(): ?DecimalNumber
    {
        return $this->customPrice;
    }

    /**
     * @param string $customPrice
     *
     * @return static
     */
    public function setCustomPrice(string $customPrice): self
    {
        $this->customPrice = new DecimalNumber($customPrice);

        return $this;
    }
}
