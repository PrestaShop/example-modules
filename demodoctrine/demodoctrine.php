<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);

use PrestaShop\Module\DemoViewOrderHooks\Collection\OrderCollection;
use PrestaShop\Module\DemoViewOrderHooks\Install\InstallerFactory;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrderLinkPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrderReviewPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrdersPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\PackageLocationsPresenter;
use PrestaShop\Module\DemoViewOrderHooks\Presenter\OrderSignaturePresenter;
use PrestaShop\Module\DemoViewOrderHooks\Repository\OrderRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\OrderReviewRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\PackageLocationRepository;
use PrestaShop\Module\DemoViewOrderHooks\Repository\OrderSignatureRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

// need it because InstallerFactory is not autoloaded during the install
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class DemoDoctrine extends Module
{
    public function __construct()
    {
        $this->name = 'demodoctrine';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.6.5', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Demo doctrine');
        $this->description = $this->l('Demonstration of Doctrine entities in PrestaShop 1.7.7');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminDemodoctrineQuote')
        );
    }

    public function hookDisplayHome()
    {
        $repository = $this->get('prestashop.module.demodoctrine.repository.quote_repository');
        $langId = $this->context->language->id;
        $quotes = $repository->getRandom($langId, 3);

        $this->smarty->assign(['quotes' => $quotes]);

        return $this->fetch('module:demodoctrine/views/templates/front/home.tpl');
    }

    /**
     * Render a twig template.
     */
    private function render(string $template, array $params = []): string
    {
        /** @var Twig_Environment $twig */
        $twig = $this->get('twig');

        return $twig->render($template, $params);
    }

    /**
     * Get path to this module's template directory
     */
    private function getModuleTemplatePath(): string
    {
        return sprintf('@Modules/%s/views/templates/admin/', $this->name);
    }
}
