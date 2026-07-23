/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

// Spike #41970 PoC — the module manages its own assets and chart initialization,
// loaded from its hook output (see toolbar.html.twig), not via actionAdminControllerSetMedia.
// Colors below approximate the BO's prestakit design tokens ($primary/$secondary/...);
// a real integration would read the actual computed CSS custom properties instead.
(function () {
  var PALETTE = {
    primary: '#2b7dd8',
    secondary: '#6c757d',
    success: '#2ecc71',
    info: '#3ea8f5',
    warning: '#f4b740',
    danger: '#e6544a',
  };

  function readJson(id) {
    var el = document.getElementById(id);
    return el ? JSON.parse(el.textContent) : null;
  }

  function initDoughnut() {
    var canvas = document.getElementById('dashexamplechartjs-doughnut');
    var data = readJson('dashexamplechartjs-doughnut-data');
    if (!canvas || !data || typeof Chart === 'undefined') {
      return;
    }

    new Chart(canvas, {
      type: 'doughnut',
      data: {
        labels: data.labels,
        datasets: [{
          data: data.values,
          backgroundColor: [PALETTE.primary, PALETTE.info, PALETTE.warning, PALETTE.secondary],
        }],
      },
      options: {
        plugins: { legend: { position: 'bottom' } },
      },
    });
  }

  function initLine() {
    var canvas = document.getElementById('dashexamplechartjs-line');
    var data = readJson('dashexamplechartjs-line-data');
    if (!canvas || !data || typeof Chart === 'undefined') {
      return;
    }

    new Chart(canvas, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Current period',
            data: data.current,
            borderColor: PALETTE.primary,
            backgroundColor: 'rgba(43, 125, 216, 0.15)',
            fill: true,
            tension: 0.3,
          },
          {
            label: 'Previous period',
            data: data.previous,
            borderColor: PALETTE.secondary,
            borderDash: [6, 4],
            fill: false,
            tension: 0.3,
          },
        ],
      },
      options: {
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  function initBar() {
    var canvas = document.getElementById('dashexamplechartjs-bar');
    var data = readJson('dashexamplechartjs-bar-data');
    if (!canvas || !data || typeof Chart === 'undefined') {
      return;
    }

    new Chart(canvas, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [
          { label: 'Goal (%)', data: data.goal, backgroundColor: PALETTE.secondary },
          { label: 'Actual (%)', data: data.actual, backgroundColor: PALETTE.success },
        ],
      },
      options: {
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initDoughnut();
    initLine();
    initBar();
  });
})();
