$(() => {
  // initialize the Router component in PS 1.7.8+ 
  if (typeof window.prestashop.component !== 'undefined') { window.prestashop.component.initComponents(['Router']); }
 
  // initiate the search on button click
  $(document).on('click', '#demo_search_customer_btn', () => search($('#demo_search_customer').val()));

  /**
   * Performs ajax request to search for customers by search phrase
   *
   * @param searchPhrase
   */
  function search(searchPhrase) {
    var route;
    var getParams = {'customer_search': searchPhrase};
    
    if (typeof window.prestashop.component !== 'undefined') {
      // use the router component to generate the existing route in PS 1.7.8+
      route = window.prestashop.instance.router.generate('admin_customers_search');
    } else {
      // use pure JS functions and bare PS search route if component is unavailable
      const locationSearch = new URLSearchParams(window.location.search);
      const locationPathname = window.location.pathname.split('/');
      
      for (const param of locationSearch) {
        if (param[0] === '_token') getParams[param[0]] = param[1];
      }
      route = `${locationPathname[0]}/${locationPathname[1]}/sell/customers/search`;
	}
    
    // use the ajax request to get customers
    $.get(route, getParams
      // render the customers
    ).then((data) => renderResults(data));
  }

  /**
     * Renders the results block
     *
     * @param {Object} data
     */
  function renderResults(data) {
    var $infoBlock = $('#info-block')
    $infoBlock.addClass('d-none').empty();
    var $resultsBlock = $('#customers-results');
    var $resultsBody = $('#customers-results tbody');

    if (data.found === false) {
      $infoBlock.text('No customers found').removeClass('d-none');
      $resultsBlock.addClass('d-none');
      $resultsBody.empty();

      return;
    }

    $resultsBlock.removeClass('d-none');
    $resultsBody.empty();

    for (const id in data.customers) {
      const customer = data.customers[id];
      $resultsBody.append(`<tr><td>${customer.email}</td><td>${customer.firstname} ${customer.lastname}</td></tr>`);
    }
  }
});
