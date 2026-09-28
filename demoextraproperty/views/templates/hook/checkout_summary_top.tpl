{*
  Cart extra field on the checkout summary.

  $cart is the presented CartLazyArray, a Smarty global on every FO page. Extra properties
  are exposed under its `extra_properties` key (snake_case — the camelCase `extraProperties`
  spelling exists only in the Admin API JSON). displayFront filtering is native: a
  displayFront=false cart field would never reach this template.
*}
{if isset($cart.extra_properties.demoextrafield.delivery_note) && $cart.extra_properties.demoextrafield.delivery_note}
  <section class="demoextrafield demoextrafield--cart" style="margin-bottom: 1rem;">
    <h4>{l s='Delivery note (demoextrafield)' d='Modules.Demoextrafield.Main'}</h4>
    <p>{$cart.extra_properties.demoextrafield.delivery_note|escape:'htmlall':'UTF-8'}</p>
  </section>
{/if}
