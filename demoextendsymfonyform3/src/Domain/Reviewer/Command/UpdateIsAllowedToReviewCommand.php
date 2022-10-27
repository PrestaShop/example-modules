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
 * used to update customers review status.
 *
 * @see \DemoCQRSHooksUsage\Domain\Reviewer\CommandHandler\UpdateIsAllowedToReviewHandler how the data is handled.
 */
class UpdateIsAllowedToReviewCommand
{
    /**
     * @var CustomerId
     */
    private $customerId;

    /**
     * @var bool
     */
    private $isAllowedToReview;

    /**
     * @param int $customerId
     * @param bool $isAllowedToReview
     *
     * @throws CustomerException
     */
    public function __construct($customerId, $isAllowedToReview)
    {
        $this->customerId = new CustomerId($customerId);
        $this->isAllowedToReview = $isAllowedToReview;
    }

    /**
     * @return CustomerId
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return bool
     */
    public function isAllowedToReview()
    {
        return $this->isAllowedToReview;
    }
}
