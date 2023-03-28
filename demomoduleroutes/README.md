# Demo Moduleroutes

## About

This module illustrates using the [moduleRoutes hook](https://devdocs.prestashop-project.org/8/modules/concepts/hooks/list-of-hooks/moduleroutes/) in a module.

It creates two `ModuleFrontController` controllers, extends default PrestaShop routes with two custom ones, and maps them to those controllers.

You can find more information in [moduleRoutes hook on the devdocs](https://devdocs.prestashop-project.org/8/modules/concepts/hooks/list-of-hooks/moduleroutes/).

## Supported PrestaShop versions

`hookModuleRoutes` was added in PrestaShop 1.5.3, but this module is compatible with 8.0.0 and above versions.

### How to install

- Copy the module into `modules` directory of your PrestaShop installation
- Install the module from Back Office or from CLI

### How to test

- Enable `Friendly URL` in Back Office > Shop Parameters > Traffic & Seo
- Access: 
    - _yourdomain_/_installdir_/demomoduleroutes/list
    - _yourdomain_/_installdir_/demomoduleroutes/show/1/abc