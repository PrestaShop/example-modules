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

use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotCreateReviewerException;
use DemoCQRSHooksUsage\Entity\Reviewer;

/**
 * Holds the abstraction for common actions for reviewer commands.
 */
class AbstractReviewerHandler
{
    /**
     * Creates a reviewer.
     *
     * @param $customerId
     *
     * @return Reviewer
     *
     * @throws CannotCreateReviewerException
     */
    protected function createReviewer($customerId)
    {
        try {
            $reviewer = new Reviewer();
            $reviewer->id_customer = $customerId;
            $reviewer->is_allowed_for_review = 0;

            if (false === $reviewer->save()) {
                throw new CannotCreateReviewerException(
                    sprintf(
                        'An error occurred when creating reviewer with customer id "%s"',
                        $customerId
                    )
                );
            }
        } catch (PrestaShopException $exception) {
            /*
             * @see https://devdocs.prestashop.com/1.7/development/architecture/domain-exceptions/
             */
            throw new CannotCreateReviewerException(
                sprintf(
                    'An unexpected error occurred when creating reviewer with customer id "%s"',
                    $customerId
                ),
                0,
                $exception
            );
        }

        return $reviewer;
    }
}
