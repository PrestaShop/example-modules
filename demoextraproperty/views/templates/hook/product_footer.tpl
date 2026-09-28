<section class="demoextrafield demoextrafield--date-last-seen">
  <h4>{l s='Date last seen (extra field demo)' d='Modules.Demoextrafield.Admin'}</h4>
  <ul>
    <li>
      <strong>{l s='Previous value' d='Modules.Demoextrafield.Admin'}:</strong>
      {if $dateLastSeen}
        <span>{$dateLastSeen|escape:'htmlall':'UTF-8'}</span>
      {else}
        <em>{l s='Never seen before' d='Modules.Demoextrafield.Admin'}</em>
      {/if}
    </li>
    <li>
      <strong>{l s='Updated to' d='Modules.Demoextrafield.Admin'}:</strong>
      <span>{$dateLastSeenUpdated|escape:'htmlall':'UTF-8'}</span>
    </li>
  </ul>
</section>
