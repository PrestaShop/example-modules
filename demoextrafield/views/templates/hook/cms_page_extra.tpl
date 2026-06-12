<section class="demoextrafield demoextrafield--cms" style="margin: 1rem 0;">
  <h4>{l s='Extra fields (demoextrafield)' d='Modules.Demoextrafield.Main'}</h4>

  {* cmsObjectModel is a CMS ObjectModel instantiated WITH the context langId (assigned in
     hookDisplayCMSDisputeInformation): lang-scoped fields (promo_banner) are scalars for the
     current language, and the bag is FO-filtered natively. *}
  {include file='./_extra_properties.tpl' objectModel=$cmsObjectModel}

  {* Named access through the presented $cms Smarty global (built by CmsController via
     ObjectPresenter, which populates the extra_properties key on presented arrays).
     First hop uses dot syntax here because $cms is a plain array. *}
  {if isset($cms) && $cms.extra_properties.demoextrafield.promo_banner}
    <p class="demoextrafield__banner" style="font-weight: bold; margin-top: 0.5rem;">
      {$cms.extra_properties.demoextrafield.promo_banner|escape:'htmlall':'UTF-8'}
    </p>
  {/if}
</section>
