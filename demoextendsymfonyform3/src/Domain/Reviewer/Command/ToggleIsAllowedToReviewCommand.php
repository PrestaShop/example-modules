<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Domain\Reviewer\Command;

use PrestaShop\PrestaShop\Core\Domain\Customer\ValueObject\CustomerId;

/**
 * Used for toggling the customer if is allowed to make a review.
 *
 * @see \DemoCQRSHooksUsage\Domain\Reviewer\CommandHandler\ToggleIsAllowedToReviewHandler how the data is handled.
 */
class ToggleIsAllowedToReviewCommand
{
    private CustomerId $customerId;

    /**
     * @throws CustomerException
     */
    public function __construct(int $customerId)
    {
        $this->customerId = new CustomerId($customerId);
    }

    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }
}
