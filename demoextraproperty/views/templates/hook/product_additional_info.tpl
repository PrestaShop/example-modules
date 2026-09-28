<section class="demoextraproperty demoextraproperty--product">
  <h4>{l s='Extra properties (demoextraproperty)' d='Modules.Demoextraproperty.Main'}</h4>

  {* 1. Generic loop — iterates over all fields registered by this module. *}
  {include file='./_extra_properties.tpl' objectModel=$product}

  {* 2. Named access — read a specific field directly without looping.
        Useful when you need to act on a known field (conditional display, formatting, etc.). *}
  {if $product.extra_properties.demoextraproperty.is_dangerous|intval}
    <p class="demoextraproperty__warning" style="color: #c0392b; font-weight: bold; margin-top: 0.5rem;">
      {l s='⚠ This product is marked as dangerous.' d='Modules.Demoextraproperty.Admin'}
    </p>
  {/if}
</section>
