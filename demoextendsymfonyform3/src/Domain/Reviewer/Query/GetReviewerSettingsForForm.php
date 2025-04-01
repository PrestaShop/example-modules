<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Domain\Reviewer\Query;

use PrestaShop\PrestaShop\Core\Domain\Customer\ValueObject\CustomerId;

/**
 * Gets reviewer settings data ready for form display.
 *
 * @see \DemoCQRSHooksUsage\Domain\Reviewer\QueryHandler\GetReviewerSettingsForFormHandler how the data is retrieved.
 */
class GetReviewerSettingsForForm
{
    private ?CustomerId $customerId;

    public function __construct(?int $customerId)
    {
        $this->customerId = null !== $customerId ? new CustomerId((int) $customerId) : null;
    }

    public function getCustomerId(): ?CustomerId
    {
        return $this->customerId;
    }
}
