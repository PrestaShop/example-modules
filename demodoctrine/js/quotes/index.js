/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

import Grid from '@components/grid/grid';
import ReloadListActionExtension from '@components/grid/extension/reload-list-extension';
import ExportToSqlManagerExtension from '@components/grid/extension/export-to-sql-manager-extension';
import FiltersResetExtension from '@components/grid/extension/filters-reset-extension';
import SortingExtension from '@components/grid/extension/sorting-extension';
import LinkRowActionExtension from '@components/grid/extension/link-row-action-extension';
import SubmitGridExtension from '@components/grid/extension/submit-grid-action-extension';
import SubmitBulkExtension from '@components/grid/extension/submit-bulk-action-extension';
import BulkActionCheckboxExtension from '@components/grid/extension/bulk-action-checkbox-extension';
import SubmitRowActionExtension from '@components/grid/extension/action/row/submit-row-action-extension';

const $ = window.$;

$(() => {
  const quotesGrid = new Grid('quote');

  quotesGrid.addExtension(new ReloadListActionExtension());
  quotesGrid.addExtension(new ExportToSqlManagerExtension());
  quotesGrid.addExtension(new FiltersResetExtension());
  quotesGrid.addExtension(new SortingExtension());
  quotesGrid.addExtension(new LinkRowActionExtension());
  quotesGrid.addExtension(new SubmitGridExtension());
  quotesGrid.addExtension(new SubmitBulkExtension());
  quotesGrid.addExtension(new BulkActionCheckboxExtension());
  quotesGrid.addExtension(new SubmitRowActionExtension());
});
