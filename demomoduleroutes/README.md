# Demo Moduleroutes

## About

This module illustrates using the [moduleRoutes hook](https://devdocs.prestashop-project.org/9/modules/concepts/hooks/list-of-hooks/moduleroutes/) in a module.

It creates two `ModuleFrontController` controllers, extends default PrestaShop routes with two custom ones, and maps them to those controllers.

You can find more information in [moduleRoutes hook on the devdocs](https://devdocs.prestashop-project.org/9/modules/concepts/hooks/list-of-hooks/moduleroutes/).

## Supported PrestaShop versions

`hookModuleRoutes` was added in PrestaShop 1.5.3, but this module is compatible with 8.0.0 and above versions.

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demomoduleroutes`*
3. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demomoduleroutes`

_* Because the name of the directory and the name of the main module file must match._

### How to test

- Enable `Friendly URL` in Back Office > Shop Parameters > Traffic & Seo
- Click **Configure** on the module in Module Manager — the configuration page displays ready-to-click links to both demo URLs for your installation.
- Or access them manually:
    - _yourdomain_/_installdir_/demomoduleroutes/list
    - _yourdomain_/_installdir_/demomoduleroutes/show/1/abc