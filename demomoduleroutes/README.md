# Demo Moduleroutes

## About

This module illustrates how to use the [moduleRoutes hook](https://devdocs.prestashop-project.org/8/modules/concepts/hooks/list-of-hooks/moduleroutes/) in a module.

It creates two `ModuleFrontController` controllers, and extend default PrestaShop routes with two custom ones and map them to the `ModuleFrontController` controllers.

More explainations about usage in [moduleRoutes hook on devdocs](https://devdocs.prestashop-project.org/8/modules/concepts/hooks/list-of-hooks/moduleroutes/).

## Supported PrestaShop versions

Compatible with 1.5.3.0 and above versions.

### How to install

- Copy the module into `modules` directory of your PrestaShop installation
- Install the module from Back Office or from CLI

### How to test

- Enable `Friendly URL` in Back Office > Shop Parameters > Traffic & Seo
- Access: 
    - _yourdomain_/_installdir_/demomoduleroutes/list
    - _yourdomain_/_installdir_/demomoduleroutes/show/1/abc