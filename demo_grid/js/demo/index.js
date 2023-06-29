/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

// Since PrestaShop 1.7.8 you can import components below directly from the window object.
// This is the recommended way to use them as you won't have to do any extra configuration or compilation.
// We keep the old way of importing them for backward compatibility.
// @see: https://devdocs.prestashop-project.org/1.7/development/components/global-components/
import Grid from '@PSJs/components/grid/grid';
import LinkRowActionExtension from '@PSJs/components/grid/extension/link-row-action-extension';
import SubmitRowActionExtension from '@PSJs/components/grid/extension/action/row/submit-row-action-extension';
import SortingExtension from '@PSJs/components/grid/extension/sorting-extension';
import PositionExtension from '@PSJs/components/grid/extension/position-extension';
import FiltersResetExtension from '@PSJs/components/grid/extension/filters-reset-extension';
const { $ } = window

$(() => {
  const grid = new Grid('product')

  grid.addExtension(new SortingExtension());
  grid.addExtension(new LinkRowActionExtension());
  grid.addExtension(new SubmitRowActionExtension());
  grid.addExtension(new PositionExtension());
  grid.addExtension(new FiltersResetExtension());
});
