/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

/**
 * Three mode available:
 *  build = production mode
 *  build:analyze = production mode with bundler analyzer
 *  dev = development mode
 */
module.exports = () => (
  process.env.NODE_ENV === 'production' ?
    require('./.webpack/prod.js')() :
    require('./.webpack/dev.js')()
);
