$(() => {
  $(document).on('click', '#demo_search_customer_btn', () => {
    var route = window.prestashop.instance.router.generate('admin_customers_search');
    //@todo: there is no router initiated :/ need to get back to this module when this is fixed.
  });
});
