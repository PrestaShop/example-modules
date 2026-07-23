# Dashboard example - Chart.js (`dashexamplechartjs`)

PoC for spike [PrestaShop/PrestaShop#41970](https://github.com/PrestaShop/PrestaShop/issues/41970) — validates
**Chart.js** as the replacement for D3 v3 + NVD3 on the migrated (Symfony) Back Office Dashboard, using the
same integration contract as the reference [`dashexample`](../dashexample) module from
[PrestaShop/PrestaShop#41968](https://github.com/PrestaShop/PrestaShop/issues/41968) /
[#254](https://github.com/PrestaShop/example-modules/pull/254).

## What it demonstrates

- Registers on the new dashboard hooks: `displayAdminDashboardZoneOne`, `displayAdminDashboardZoneTwo`,
  `displayAdminDashboardToolbar`. No legacy Smarty/`HelperForm`/`Db::getInstance()`.
- Renders hook content through module Twig templates (`$this->get('twig')`, `@Modules/dashexamplechartjs/...`).
- Loads **Chart.js v4.5.1 (MIT), self-hosted** (`views/js/lib/chart.umd.min.js`, no CDN dependency) from
  its own hook output (toolbar hook), exactly like `dashexample` loads its assets — no
  `actionAdminControllerSetMedia`, no `get_class($controller)` detection.
- Covers the 4 chart types the current native widgets need:
  - Zone One: doughnut chart → `dashactivity` equivalent (traffic sources).
  - Zone Two: line/area chart with a previous-period overlay → `dashtrends` equivalent (sales trend),
    plus a bar chart → `dashgoals` equivalent (goals vs. actual).
- Sample/static data only (no Doctrine queries) — this is a rendering/integration PoC, not a full
  reimplementation of the native widgets' data layer.

## Requirements

- PrestaShop **9.2.0+**, with core PR [PrestaShop/PrestaShop#42077](https://github.com/PrestaShop/PrestaShop/pull/42077)
  (Symfony `DashboardController`, routing, Twig layout, feature flag, 5 new hooks) merged.
- The `dashboard` feature flag enabled (*Advanced Parameters > New & Experimental Features*).

## Install

```bash
php bin/console prestashop:module install dashexamplechartjs
```

Then enable the `dashboard` feature flag and open the Dashboard.

## Known issues on the dependency (PrestaShop/PrestaShop#42077)

Building this PoC surfaced two bugs in the core PR this module depends on (reported there):

- `index.html.twig` is missing `|raw` on `toolbarContent`/`topContent`, so any HTML a module returns from
  `displayAdminDashboardToolbar` or `displayAdminDashboardTop` renders escaped instead of executing — this
  breaks the toolbar-based asset-loading pattern this module uses (and that `dashexample` uses too). Until
  fixed upstream, this module's assets won't actually load.
- The date-range calendar form loses its security token on submit (`method="get"` form whose `action` already
  contains `?_token=...`, which gets dropped and rebuilt from the form fields alone), redirecting to
  `security/compromised`.

## Chart.js benchmark

Chart.js was selected as the primary recommendation among Chart.js / ApexCharts / Billboard.js: MIT license,
~67.5k★ on GitHub, ~54M downloads/month, ~68 KB gzip, no license risk — over ApexCharts (excluded: proprietary
relicensing in June 2025, revenue-gated + no-redistribution clause) and Billboard.js (heavier bundle, smaller
community, keeps a D3 v7 dependency).

**Known gap surfaced by this PoC**: Chart.js renders on `<canvas>`, which is invisible to screen readers by
default. This PoC adds `role="img"` + `aria-label` on each canvas as a minimal baseline, but a real migration
should budget explicit accessibility work per chart.
