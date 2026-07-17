/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

document.addEventListener('DOMContentLoaded', () => {
  // The module manages its own assets: this file is loaded from the module's hook output
  // (see views/templates/admin/toolbar.html.twig), not via actionAdminControllerSetMedia.
  console.log('[dashexample] Loaded on the migrated Symfony dashboard page.');
});
