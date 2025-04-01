<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

namespace DemoCQRSHooksUsage\Controller\Admin;

use DemoCQRSHooksUsage\Domain\Reviewer\Command\ToggleIsAllowedToReviewCommand;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotCreateReviewerException;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotToggleAllowedToReviewStatusException;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\ReviewerException;
use PrestaShop\PrestaShop\Core\Domain\Customer\Exception\CustomerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;

/**
 * This controller holds all custom actions which are added by extending "Sell > Customers" page.
 *
 * @see https://devdocs.prestashop-project.org/9/modules/concepts/controllers/admin-controllers/ for more details.
 */
class CustomerReviewController extends PrestaShopAdminController
{
    /**
     * Catches the toggle action of customer review.
     */
    public function toggleIsAllowedForReviewAction(int $customerId): JsonResponse
    {
        try {
            /*
             * This part demonstrates the usage of CQRS pattern command to perform write operation for Reviewer entity.
             * @see https://devdocs.prestashop-project.org/9/development/architecture/domain/cqrs/ for more detailed information.
             *
             * As this is our recommended approach of writing the data but we not force to use this pattern in modules -
             * you can use directly an entity here or wrap it in custom service class.
             */
            $this->dispatchCommand(new ToggleIsAllowedToReviewCommand((int) $customerId));

            return $this->json(
                [
                    'status' => true,
                    'message' => $this->trans('Successful update.', [], 'Admin.Notifications.Success')
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
     * @see https://devdocs.prestashop-project.org/9/development/architecture/domain/domain-exceptions/ for more detailed explanation
     */
    private function getErrorMessageMapping()
    {
        return [
            CustomerException::class => $this->trans(
                'Something bad happened when trying to get customer id',
                [],
                'Modules.Democqrshooksusage.Customerreviewcontroller'
            ),
            CannotCreateReviewerException::class => $this->trans(
                'Failed to create reviewer',
                [],
                'Modules.Democqrshooksusage.Customerreviewcontroller'
            ),
            CannotToggleAllowedToReviewStatusException::class => $this->trans(
                'An error occurred while updating the status.',
                [],
                'Modules.Democqrshooksusage.Customerreviewcontroller'
            ),
        ];
    }
}
