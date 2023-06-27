/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

// Since PrestaShop 1.7.8 you can import components below directly from the window object.
// This is the recommended way to use them as you won't have to do any extra configuration or compilation.
// We keep the old way of importing them for backward compatibility.
// @see: https://devdocs.prestashop-project.org/1.7/development/components/global-components/
import TranslatableInput from '@components/translatable-input';

const $ = window.$;

$(() => {
  new TranslatableInput();
});
