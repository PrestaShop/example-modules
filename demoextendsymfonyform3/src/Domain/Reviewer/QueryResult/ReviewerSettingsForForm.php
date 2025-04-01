<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Domain\Reviewer\QueryResult;

/**
 * Holds data used in modified customers form.
 */
class ReviewerSettingsForForm
{
    public function __construct(
        private bool $isAllowedForReview
    ) {
    }

    public function isAllowedForReview(): bool
    {
        return $this->isAllowedForReview;
    }
}
