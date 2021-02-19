# example_module_mailtheme
Example module to add a Mail theme to PrestaShop.

# Requirements

This module requires PrestaShop 1.7.6 to work correctly.

# Install

```bash
$ cd {PRESTASHOP_FOLDER}/modules
$ git clone git@github.com:PrestaShop/example_module_mailtheme.git
$ cd example_module_mailtheme
$ composer install
$ cd {PRESTASHOP_FOLDER}
$ php ./bin/console prestashop:module install example_module_mailtheme
```

# Build assets

Build assets for production

```bash
$ npm install
$ npm run build
```

Build assets for development

```bash
$ npm install
$ npm run watch
```
