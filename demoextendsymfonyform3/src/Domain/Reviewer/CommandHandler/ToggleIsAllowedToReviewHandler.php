<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Domain\Reviewer\CommandHandler;

use DemoCQRSHooksUsage\Domain\Reviewer\Command\ToggleIsAllowedToReviewCommand;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotCreateReviewerException;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotToggleAllowedToReviewStatusException;
use DemoCQRSHooksUsage\Entity\Reviewer;
use DemoCQRSHooksUsage\Repository\ReviewerRepository;
use PrestaShop\PrestaShop\Core\CommandBus\Attributes\AsCommandHandler;
use PrestaShopException;

/**
 * Used for toggling the customer if is allowed to make a review.
 */
#[AsCommandHandler]
class ToggleIsAllowedToReviewHandler extends AbstractReviewerHandler
{
    public function __construct(
        private readonly ReviewerRepository $reviewerRepository
    ) {
    }

    /**
     * @throws CannotCreateReviewerException
     * @throws CannotToggleAllowedToReviewStatusException
     */
    public function handle(ToggleIsAllowedToReviewCommand $command): void
    {
        $reviewerId = $this->reviewerRepository->findIdByCustomer($command->getCustomerId()->getValue());

        $reviewer = new Reviewer($reviewerId);

        if (0 >= $reviewer->id) {
            $reviewer = $this->createReviewer($command->getCustomerId()->getValue());
        }

        $reviewer->is_allowed_for_review = (bool) !$reviewer->is_allowed_for_review;

        try {
            if (false === $reviewer->update()) {
                throw new CannotToggleAllowedToReviewStatusException(
                    sprintf('Failed to change status for reviewer with id "%s"', $reviewer->id)
                );
            }
        } catch (PrestaShopException $exception) {
            /*
             * @see https://devdocs.prestashop-project.org/9/development/architecture/domain/domain-exceptions/
             */
            throw new CannotToggleAllowedToReviewStatusException(
                'An unexpected error occurred when updating reviewer status'
            );
        }
    }
}
