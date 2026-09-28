{*
  Order extra properties on the customer's order detail page.

  $orderObjectModel is the raw Order ObjectModel (assigned in hookDisplayOrderDetail): the
  bag resolves the entity through its physical table (orders / id_order) even though the
  definition was registered as 'order'. The delivery_note was copied from the cart by
  hookActionValidateOrder — the intended pattern for cart values that must survive checkout.
*}
<section class="demoextraproperty demoextraproperty--order" style="margin-bottom: 1rem;">
  <h4>{l s='Extra properties (demoextraproperty)' d='Modules.Demoextraproperty.Main'}</h4>
  {include file='./_extra_properties.tpl' objectModel=$orderObjectModel}
</section>
