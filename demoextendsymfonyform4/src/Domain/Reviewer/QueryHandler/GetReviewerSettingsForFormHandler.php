<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Domain\Reviewer\QueryHandler;

use DemoCQRSHooksUsage\Domain\Reviewer\Query\GetReviewerSettingsForForm;
use DemoCQRSHooksUsage\Domain\Reviewer\QueryResult\ReviewerSettingsForForm;
use DemoCQRSHooksUsage\Repository\ReviewerRepository;
use PrestaShop\PrestaShop\Core\CommandBus\Attributes\AsQueryHandler;

/**
 * Gets reviewer settings data ready for form display.
 */
#[AsQueryHandler]
class GetReviewerSettingsForFormHandler
{
    public function __construct(private readonly ReviewerRepository $reviewerRepository)
    {
    }

    public function handle(GetReviewerSettingsForForm $query)
    {
        if (null === $query->getCustomerId()) {
            return new ReviewerSettingsForForm(false);
        }

        return new ReviewerSettingsForForm(
            $this->reviewerRepository->getIsAllowedToReviewStatus($query->getCustomerId()->getValue())
        );
    }
}
