Demo View Order Hooks
=====================

This module was created in order to demonstrate how to use the new hooks introduced with the new "View an Order" back-office page in PrestaShop 1.7.7.0 .

It uses the following hooks:
- displayBackOfficeOrderActions
- displayAdminOrderTabContent
- displayAdminOrderTabLink
- displayAdminOrderMain
- displayAdminOrderSide
- displayAdminOrder
- displayAdminOrderTop
- actionGetAdminOrderButtons

Please note this module is an example only, not a mandatory structure.

## Hooks usage

### displayBackOfficeOrderActions

We use this hook to display scanned customer signature.

### displayAdminOrderTabLink

We use this hook to display a "Package tracking" tab.
The content of this tab is provided in displayAdminOrderTabContent hook.

### displayAdminOrderTabContent

We use this hook to display an additional listing in the page.
The listing displays package tracking informations.
It is controlled and linked to displayAdminOrderTabLink tab.

### displayAdminOrderMain

We use this hook to display an additional listing in the page.
The listing displays customer _delivered_ orders.

### displayAdminOrderSide

We use this hook to display a widget showing customer's review.

### displayAdminOrder

We use this hook to display an additional listing in the page.
The listing displays other orders from the same customer.

### displayAdminOrderTop

We use this hook to display blue previous/next order buttons in the top to ease the navigation.
Please note Order page already contains these buttons, but we put some additional buttons even easier to spot.

### actionGetAdminOrderButtons

TODO

## Other examples

In this module, we also demonstrated other usecases that you might find useful for building a module. However they are not mandatory to use in your module.

### Using Install Factory to handle install/uninstall steps

We put the install/uninstall logic of this module into a dedicated class for a better concerns separation, following [SOLID](https://en.wikipedia.org/wiki/SOLID) principles/

### Using Doctrine ORM to handle persisted models

We used [Doctrine ORM](https://github.com/doctrine/orm) to manage the persistence of multiple models, instead of PrestaShop ObjectModel ORM.

### Using PHPStan to monitor code quality

We added [PHPStan](https://github.com/phpstan/phpstan) to monitor the level of quality of this module code.