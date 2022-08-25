<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

use PrestaShop\Module\DemoExtendGrid\Install\Installer;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnInterface;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Exception\ColumnNotFoundException;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class DemoExtendGrid extends Module
{
    public function __construct()
    {
        $this->name = 'demoextendgrid';
        $this->author = 'PrestaShop';
        $this->version = '1.1.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('Demo extend grid');
        $this->description = $this->l('Demonstration of how to extend grids');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    public function hookActionAdminControllerSetMedia(array $params)
    {
        // check if it is orders controller
        if ($this->context->controller->controller_name !== 'AdminOrders') {
            return;
        }
        $action = Tools::getValue('action');

        // check if it is orders index page (we want to skip if it is `order create` or `order view` page)
        if ($action === 'vieworder' || $action === 'addorder') {
            return;
        }

        // now we are sure it is Orders index (listing) page where we need our javascript
        $this->context->controller->addJS('modules/' . $this->name . '/views/js/orders-listing.js');
    }

    /**
     * @param array $params
     */
    public function hookActionOrderGridDefinitionModifier(array $params): void
    {
        /** @var GridDefinitionInterface $orderGridDefinition */
        $orderGridDefinition = $params['definition'];

        /** @var RowActionCollectionInterface $actionsCollection */
        $actionsCollection = $this->getActionsColumn($orderGridDefinition)->getOptions()['actions'];
        $actionsCollection->add(
            // mark order is just an example of some custom action
            (new SubmitRowAction('mark_order'))
                ->setName($this->trans('Mark', [], 'Admin.Actions'))
                ->setIcon('push_pin')
                ->setOptions([
                    'route' => 'demo_admin_orders_mark_order',
                    'route_param_name' => 'orderId',
                    'route_param_field' => 'id_order',
                    // use this if you want to show the action inline instead of adding it to dropdown
                    'use_inline_display' => true,
                ])
        );
        // Button is not working by default, because SubmitRowActionExtension component is not loaded in Orders grid javascript part.
        // To replace that behavior there is an example of custom javascript in views/orders-listing.js
        // Adding grid extension in non-compiled javascript is not supported yet, we hope to fix it in future.
    }

    private function getActionsColumn(GridDefinitionInterface $gridDefinition): ColumnInterface
    {
        try {
            /** @var ColumnInterface $column */
            foreach ($gridDefinition->getColumns() as $column) {
                if ('actions' === $column->getId()) {
                    return $column;
                }
            }
        } catch (ColumnNotFoundException $e) {
            // It is possible that not every grid will have actions column.
            // In this case you can create a new column or throw exception depending on your needs
            throw $e;
        }
    }
}
