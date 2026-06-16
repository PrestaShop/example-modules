<section class="demoextrafield demoextrafield--customer" style="margin-bottom: 1rem;">
  <h4>{l s='Extra fields (demoextrafield)' d='Modules.Demoextrafield.Main'}</h4>

  {* customerObjectModel is the raw Customer ObjectModel (assigned in hookDisplayCustomerAccountTop) —
     no presenter, no array conversion. We are in a front-office controller, so the bag is
     FO-filtered natively: internal_note (displayFront=false) never appears here. *}
  {include file='./_extra_properties.tpl' objectModel=$customerObjectModel}
</section>
