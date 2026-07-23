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
 * Spike #41970 PoC: demonstrates replacing D3 v3 + NVD3 with Chart.js on the migrated
 * (Symfony) Back Office Dashboard, through the same dedicated hook family used by the
 * `dashexample` module (see PrestaShop/PrestaShop#41968 and PrestaShop/example-modules#254).
 *
 * Chart.js itself is self-hosted (views/js/lib/chart.umd.min.js, v4.5.1, MIT) and loaded
 * once from the toolbar hook, exactly like `dashexample` loads its own assets from there.
 */
class DashExampleChartjs extends Module
{
    public function __construct()
    {
        $this->name = 'dashexamplechartjs';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '9.2.0', 'max' => '9.99.99'];
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Dashboard example - Chart.js');
        $this->description = $this->l('Spike #41970 PoC: renders the migrated Symfony dashboard zones with Chart.js instead of D3 v3 / NVD3.');
    }

    public function install(): bool
    {
        return parent::install()
            && $this->registerHook([
                'displayAdminDashboardZoneOne',
                'displayAdminDashboardZoneTwo',
                'displayAdminDashboardToolbar',
            ]);
    }

    /**
     * Zone One: doughnut chart (traffic sources) — the dashactivity-equivalent widget.
     */
    public function hookDisplayAdminDashboardZoneOne(array $params): string
    {
        return $this->render('zone_one.html.twig', [
            'dateFrom' => $params['date_from'] ?? null,
            'dateTo' => $params['date_to'] ?? null,
        ]);
    }

    /**
     * Zone Two: line/area chart with a "previous period" overlay (dashtrends-equivalent)
     * plus a goals-vs-actual bar chart (dashgoals-equivalent).
     */
    public function hookDisplayAdminDashboardZoneTwo(array $params): string
    {
        return $this->render('zone_two.html.twig', [
            'dateFrom' => $params['date_from'] ?? null,
            'dateTo' => $params['date_to'] ?? null,
            'moduleUri' => $this->getPathUri(),
        ]);
    }

    /**
     * Loads Chart.js (self-hosted) and this module's own JS/CSS once, from the toolbar hook —
     * no `actionAdminControllerSetMedia`, no `get_class($controller)` detection.
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
