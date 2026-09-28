<section class="demoextraproperty demoextraproperty--date-last-seen">
  <h4>{l s='Date last seen (extra property demo)' d='Modules.Demoextraproperty.Admin'}</h4>
  <ul>
    <li>
      <strong>{l s='Previous value' d='Modules.Demoextraproperty.Admin'}:</strong>
      {if $dateLastSeen}
        <span>{$dateLastSeen|escape:'htmlall':'UTF-8'}</span>
      {else}
        <em>{l s='Never seen before' d='Modules.Demoextraproperty.Admin'}</em>
      {/if}
    </li>
    <li>
      <strong>{l s='Updated to' d='Modules.Demoextraproperty.Admin'}:</strong>
      <span>{$dateLastSeenUpdated|escape:'htmlall':'UTF-8'}</span>
    </li>
  </ul>
</section>
