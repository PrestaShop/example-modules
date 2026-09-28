{*
  Cart extra property on the checkout summary.

  $cart is the presented CartLazyArray, a Smarty global on every FO page. Extra properties
  are exposed under its `extra_properties` key (snake_case — the camelCase `extraProperties`
  spelling exists only in the Admin API JSON). displayFront filtering is native: a
  displayFront=false cart field would never reach this template.
*}
{if isset($cart.extra_properties.demoextraproperty.delivery_note) && $cart.extra_properties.demoextraproperty.delivery_note}
  <section class="demoextraproperty demoextraproperty--cart" style="margin-bottom: 1rem;">
    <h4>{l s='Delivery note (demoextraproperty)' d='Modules.Demoextraproperty.Main'}</h4>
    <p>{$cart.extra_properties.demoextraproperty.delivery_note|escape:'htmlall':'UTF-8'}</p>
  </section>
{/if}
