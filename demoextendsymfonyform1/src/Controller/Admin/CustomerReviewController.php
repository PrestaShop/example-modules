<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace PrestaShop\Module\DemoHowToExtendSymfonyForm\Controller\Admin;

use PrestaShop\Module\DemoHowToExtendSymfonyForm\Exception\CannotCreateReviewerException;
use PrestaShop\Module\DemoHowToExtendSymfonyForm\Exception\CannotToggleAllowedToReviewStatusException;
use PrestaShop\Module\DemoHowToExtendSymfonyForm\Exception\ReviewerException;
use PrestaShop\Module\DemoHowToExtendSymfonyForm\Entity\Reviewer;
use PrestaShop\PrestaShop\Core\Domain\Customer\Exception\CustomerException;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * This controller holds all custom actions which are added by extending "Sell > Customers" page.
 *
 * @see https://devdocs.prestashop.com/1.7/modules/concepts/controllers/admin-controllers/ for more details.
 */
class CustomerReviewController extends FrameworkBundleAdminController
{
    /**
     * Catches the toggle action of customer review.
     *
     * @param int $customerId
     *
     * @return RedirectResponse
     */
    public function toggleIsAllowedForReviewAction(int $customerId)
    {
        try {
            $reviewerId = $this->get('ps_demoextendsymfonyform.repository.reviewer')->findIdByCustomer($customerId);

            $reviewer = new Reviewer((int) $reviewerId);
            if (0 >= $reviewer->id) {
                $reviewer = $this->createReviewerIfNeeded($customerId);
            }
            $reviewer->is_allowed_for_review = (bool) !$reviewer->is_allowed_for_review;

            try {
                if (false === $reviewer->update()) {
                    throw new CannotToggleAllowedToReviewStatusException(
                        sprintf('Failed to change status for reviewer with id "%s"', $reviewer->id)
                    );
                }
            } catch (\PrestaShopException $exception) {
                throw new CannotToggleAllowedToReviewStatusException(
                    'An unexpected error occurred when updating reviewer status'
                );
            }

            return $this->json(
                [
                    'status' => true,
                    'message' => $this->trans('Successful update.', 'Admin.Notifications.Success')
                ]
            );
        } catch (ReviewerException $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => $this->getErrorMessageForException($e, $this->getErrorMessageMapping())
                ]
            );
        }

        return $this->redirectToRoute('admin_customers_index');
    }

    /**
     * Gets error message mappings which are later used to display friendly user error message instead of the
     * exception message.
     *
     * @return array
     */
    private function getErrorMessageMapping()
    {
        return [
            CustomerException::class => $this->trans(
                'Something bad happened when trying to get customer id',
                'Modules.DemoHowToExtendSymfonyForm.Customerreviewcontroller'
            ),
            CannotCreateReviewerException::class => $this->trans(
                'Failed to create reviewer',
                'Modules.DemoHowToExtendSymfonyForm.Customerreviewcontroller'
            ),
            CannotToggleAllowedToReviewStatusException::class => $this->trans(
                'An error occurred while updating the status.',
                'Modules.DemoHowToExtendSymfonyForm.Customerreviewcontroller'
            ),
        ];
    }

    /**
     * Creates a reviewer. Used when toggle action is used on customer whose data is empty.
     *
     * @param int $customerId
     *
     * @return Reviewer
     *
     * @throws CannotCreateReviewerException
     */
    protected function createReviewerIfNeeded(int $customerId)
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
        } catch (\PrestaShopException $exception) {
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
