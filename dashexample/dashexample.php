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

use Twig\Environment;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Demonstrates how a module integrates with the migrated (Symfony) Back Office Dashboard.
 *
 * The Symfony dashboard dispatches a dedicated hook family (`displayAdminDashboard*`), distinct
 * from the legacy `dashboardZone*` / `displayDashboard*` hooks. A module therefore knows which
 * architecture it is integrating with purely from which hook is called — no version detection,
 * no `get_class($controller)` check, no Smarty, no `HelperForm`, no `Db::getInstance()`.
 *
 * See README.md for how to stay compatible with the legacy dashboard at the same time.
 */
class DashExample extends Module
{
    public function __construct()
    {
        $this->name = 'dashexample';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '9.2.0', 'max' => '9.99.99'];
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Dashboard example');
        $this->description = $this->l('Demonstration of the new dedicated hooks of the migrated Symfony dashboard page.');
    }

    public function install(): bool
    {
        return parent::install()
            && $this->registerHook([
                'displayAdminDashboardZoneOne',
                'displayAdminDashboardZoneTwo',
                'displayAdminDashboardTop',
                'displayAdminDashboardToolbar',
            ]);
    }

    /**
     * Renders a block in the first (left) column of the Symfony dashboard.
     * Receives the employee date range selected on the page.
     */
    public function hookDisplayAdminDashboardZoneOne(array $params): string
    {
        return $this->render('zone_one.html.twig', [
            'dateFrom' => $params['date_from'] ?? null,
            'dateTo' => $params['date_to'] ?? null,
        ]);
    }

    /**
     * Renders a block in the second (center) column of the Symfony dashboard.
     */
    public function hookDisplayAdminDashboardZoneTwo(array $params): string
    {
        return $this->render('zone_two.html.twig', [
            'dateFrom' => $params['date_from'] ?? null,
            'dateTo' => $params['date_to'] ?? null,
        ]);
    }

    /**
     * Renders a full-width block in the top area of the Symfony dashboard.
     * Receives the employee date range selected on the page.
     */
    public function hookDisplayAdminDashboardTop(array $params): string
    {
        return $this->render('top.html.twig', [
            'dateFrom' => $params['date_from'] ?? null,
            'dateTo' => $params['date_to'] ?? null,
        ]);
    }

    /**
     * Renders content in the toolbar area of the Symfony dashboard.
     *
     * This hook is rendered once at the top of the page, so the module loads its own
     * assets from here (via its hook output) instead of `actionAdminControllerSetMedia`.
     */
    public function hookDisplayAdminDashboardToolbar(array $params): string
    {
        return $this->render('toolbar.html.twig', [
            'moduleUri' => $this->getPathUri(),
        ]);
    }

    /**
     * Render one of this module's admin Twig templates.
     */
    private function render(string $template, array $params = []): string
    {
        /** @var Environment $twig */
        $twig = $this->get('twig');

        return $twig->render(sprintf('@Modules/%s/views/templates/admin/%s', $this->name, $template), $params);
    }
}
