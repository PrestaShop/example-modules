## Order view page new hooks demo tutorial

### Requirements

 1. Composer, see [Composer](https://getcomposer.org/) to learn more
 
### How to install

 1. Download or clone module into `modules` directory of your PrestaShop installation
 2. Rename the directory to make sure that module directory is named `demovieworderhooks`*
 3. `cd` into module's directory and run following commands:
     - `composer install` - to download dependencies into vendor folder
	 - `composer dumpautoload` to generate autoloader for module
 4. Install module from Back Office

*Because the name of the directory and the name of the main module file must match.

### What it does

This module shows how to use new Order View page hooks:

 - displayBackOfficeOrderActions - displayed between Customer and Messages cards in the Order page
 - displayAdminOrderTabContent - for adding tab content to Order page
 - displayAdminOrderTabLink - for adding tab links for tab content
 - displayAdminOrderMain - for adding Order main information
 - displayAdminOrderSide - for adding Order side information
 - displayAdminOrder - displayed at the bottom of the Order page
 - displayAdminOrderTop - displayed at the top of the Order page

The code should help understand how to:

 - Use Doctrine (https://devdocs.prestashop.com/1.7/modules/concepts/doctrine/)
 - Use Repository classes extending Symfony EntityRepository (https://symfony.com/doc/3.4/doctrine/repository.html)
 - Use Symfony services (https://devdocs.prestashop.com/1.7/modules/concepts/services/)
 - Use Twig templates to render HTML (https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/templating-with-twig/)
 - Various design patterns: Repository, Factory, Presenter
 - Use Doctrine Collections library (https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html)
