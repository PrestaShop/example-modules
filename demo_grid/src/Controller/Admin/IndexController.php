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

declare(strict_types=1);

namespace Module\DemoGrid\Controller\Admin;

use Module\DemoGrid\Grid\Definition\Factory\ProductGridDefinitionFactory;
use Module\DemoGrid\Grid\Filters\ProductFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends FrameworkBundleAdminController
{
    /**
     * List quotes
     *
     * @param ProductFilters $filters
     *
     * @return Response
     */
    public function indexAction(ProductFilters $filters)
    {
        $quoteGridFactory = $this->get('demo_grid.grid.factory.products');
        $quoteGrid = $quoteGridFactory->getGrid($filters);

        return $this->render(
            '@Modules/demo_grid/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Product listing', 'Modules.Demogrid.Admin'),
                'quoteGrid' => $this->presentGrid($quoteGrid),
            ]
        );
    }

    /**
     * Provides filters functionality.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('demo_grid.grid.definition.factory.products'),
            $request,
            ProductGridDefinitionFactory::GRID_ID,
            'demo_grid_index'
        );
    }
}
