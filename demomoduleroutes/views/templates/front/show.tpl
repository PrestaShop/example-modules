{extends file="$layout"}

{block name='content'}
  <section id="demomoduleroutes-show">
    <p>{l s='Show controller is working. ID:' mod='demomoduleroutes'} {$id|intval}, {l s='slug:' mod='demomoduleroutes'} {$slug|escape:'html':'UTF-8'}</p>
    <p><a href="{url entity='module' name='demomoduleroutes' controller='list'}">{l s='Back to list' mod='demomoduleroutes'}</a></p>
  </section>
{/block}
