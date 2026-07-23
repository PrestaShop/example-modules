# Dashboard example (`dashexample`)

Demonstration module for the **migrated (Symfony) Back Office Dashboard** and its new dedicated hook family.

It is both living documentation of the integration contract and a validation vehicle: once installed with the `dashboard` feature flag enabled, it renders content in Zone One, Zone Two and the toolbar area of the new dashboard page.

## What it demonstrates

- Registering on the **new** dashboard hooks: `displayAdminDashboardZoneOne`, `displayAdminDashboardZoneTwo`, `displayAdminDashboardZoneThree`, `displayAdminDashboardTop`, `displayAdminDashboardToolbar`.
- Rendering hook content through **module Twig templates** (`views/templates/admin/*.html.twig`) — no Smarty, no `HelperForm`, no `Db::getInstance()`.
- Passing and using hook **parameters** (`date_from` / `date_to`, the employee stats date range).
- Loading the module's **own CSS/JS assets from its hook output** (see `toolbar.html.twig`) — no `actionAdminControllerSetMedia`, no `get_class($this->context->controller)` detection.

## Requirements

- PrestaShop **9.2.0** or later (the version that introduces the migrated dashboard and its hooks).
- The **`dashboard` feature flag** enabled: *Advanced Parameters > New & Experimental Features > Dashboard page*.

## Install

```bash
# from the PrestaShop root
php bin/console prestashop:module install dashexample
```

Then enable the `dashboard` feature flag and open the Dashboard. You should see the module's blocks in the two zones and a marker in the toolbar area.

## New vs legacy hook families

The migrated dashboard deliberately uses a **new hook family**, distinct from the legacy one. A module knows which architecture it is integrating with purely from **which hook is called** — there is no version detection to do on the module side.

| New (Symfony dashboard) | Legacy (legacy dashboard) | Area |
|---|---|---|
| `displayAdminDashboardZoneOne` | `dashboardZoneOne` | Left column |
| `displayAdminDashboardZoneTwo` | `dashboardZoneTwo` | Center column |
| `displayAdminDashboardZoneThree` | `dashboardZoneThree` | Right column |
| `displayAdminDashboardTop` | `displayDashboardTop` | Top area |
| `displayAdminDashboardToolbar` | `displayDashboardToolbarTopMenu` | Toolbar |

The legacy hooks are untouched and keep working on the legacy page (flag off).

## Supporting both PrestaShop versions in one module

To render on **both** the legacy and the migrated dashboard from a single module, register on **both** hook families and let each callback render with the matching architecture. The core calls only the hook that belongs to the currently displayed page, so the two never collide.

```php
public function install(): bool
{
    return parent::install()
        && $this->registerHook([
            // Migrated (Symfony) dashboard — Twig, no legacy helpers
            'displayAdminDashboardZoneOne',
            'displayAdminDashboardZoneTwo',
            'displayAdminDashboardToolbar',
            // Legacy dashboard — Smarty / HelperForm as before
            'dashboardZoneOne',
            'dashboardZoneTwo',
            'displayDashboardToolbarTopMenu',
        ]);
}

// Called only on the migrated page
public function hookDisplayAdminDashboardZoneOne(array $params): string
{
    return $this->get('twig')->render('@Modules/mymodule/views/templates/admin/zone_one.html.twig', $params);
}

// Called only on the legacy page
public function hookDashboardZoneOne(array $params): string
{
    // legacy Smarty rendering
    $this->smarty->assign($params);

    return $this->display(__FILE__, 'views/templates/hook/zone_one.tpl');
}
```

This module registers **only the new hooks** on purpose, so it also serves as a focused test of the new contract.

## Files

| File | Role |
|---|---|
| `dashexample.php` | Module class: hook registration + hook callbacks rendering Twig |
| `views/templates/admin/zone_one.html.twig` | Zone One block |
| `views/templates/admin/zone_two.html.twig` | Zone Two block |
| `views/templates/admin/zone_three.html.twig` | Zone Three block |
| `views/templates/admin/top.html.twig` | Top area block |
| `views/templates/admin/toolbar.html.twig` | Toolbar block + asset loading |
| `views/css/dashexample.css`, `views/js/dashexample.js` | Module-owned assets |
