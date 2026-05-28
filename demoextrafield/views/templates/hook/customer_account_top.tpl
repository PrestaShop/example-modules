<section class="demoextrafield demoextrafield--customer" style="margin-bottom: 1rem;">
  <h4>{l s='Extra fields (demoextrafield)' d='Modules.Demoextrafield.Main'}</h4>

  {* customerExtraData is built in hookDisplayCustomerAccountTop from ExtraPropertiesLazyArray.
     internal_note has displayFront=false so it is absent from this output even though it exists in DB. *}
  {include file='./_extra_properties.tpl' objectModel=$customerExtraData}
</section>
