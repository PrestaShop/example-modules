<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace Module\DemoGrid\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'product';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Products', [], 'Modules.Demogrid.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id_product'))
                ->setOptions([
                    'field' => 'id_product',
                ])
            )
            ->add(
                (new DataColumn('reference'))
                ->setName($this->trans('Reference', [], 'Modules.Demogrid.Admin'))
                ->setOptions([
                    'field' => 'reference',
                ])
            )
            ->add(
                (new DataColumn('active'))
                ->setName($this->trans('Active', [], 'Modules.Demogrid.Admin'))
                ->setOptions([
                    'field' => 'active',
                ])
            )
            ->add(
                (new ActionColumn('actions'))
                  ->setName($this->trans('Actions', [], 'Admin.Actions'))
            );
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_product', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('ID', [], 'Admin.Global'),
                    ],
                ])
                ->setAssociatedColumn('id_product')
            )
            ->add(
                (new Filter('reference', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Reference', [], 'Modules.Demogrid.Admin'),
                    ],
                ])
                ->setAssociatedColumn('reference')
            )
            ->add(
                (new Filter('active', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Active', [], 'Modules.Demogrid.Admin'),
                    ],
                ])
                ->setAssociatedColumn('active')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                  ->setTypeOptions([
                      'reset_route' => 'admin_common_reset_search_by_filter_id',
                      'reset_route_params' => [
                          'filterId' => self::GRID_ID,
                      ],
                      'redirect_route' => 'demo_grid_index',
                  ])
                  ->setAssociatedColumn('actions')
            )
        ;
    }
}
