/**
 * Copyright since 2007 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
$(() => {
  $(document).on('click', '.grid-mark-row-link, .js-submit-row-action', (event) => {
    event.preventDefault();
    var $currentTarget = $(event.currentTarget)
    $.post($currentTarget.data('url')).then((data) => {
      // For example we mark the icon by green color when the ajax succeeds
      $currentTarget.find('i').addClass('text-success');
    });
  });
});
