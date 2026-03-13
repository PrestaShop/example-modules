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
     * @throws CannotCreateReviewerException
     */
    protected function createReviewer(int $customerId): Reviewer
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
             * @see https://devdocs.prestashop-project.org/9/development/architecture/domain/domain-exceptions/
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
