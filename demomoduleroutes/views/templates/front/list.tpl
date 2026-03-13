{extends file="$layout"}

{block name='content'}
  <section id="demomoduleroutes-list">
    <p>{l s='List controller is working.' mod='demomoduleroutes'}</p>
    <p><a href="{url entity='module' name='demomoduleroutes' controller='show' params=['id' => 1, 'slug' => 'abc']}">{l s='Go to show page (id=1, slug=abc)' mod='demomoduleroutes'}</a></p>
  </section>
{/block}
