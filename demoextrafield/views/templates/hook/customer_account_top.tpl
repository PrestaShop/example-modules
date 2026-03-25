<section class="demoextrafield demoextrafield--customer" style="margin-bottom: 1rem;">
  <h3>{$demoExtraFieldTitle|escape:'htmlall':'UTF-8'}</h3>
  <p><strong>{$entityLabel|escape:'htmlall':'UTF-8'}:</strong> {$entityName|escape:'htmlall':'UTF-8'}</p>

  {if empty($moduleExtras)}
    <p><em>{l s='No extra fields found for this module.' d='Modules.Demoextrafield.Admin'}</em></p>
  {else}
    <ul>
      {foreach from=$moduleExtras key=fieldName item=fieldValue}
        <li>
          <strong>{$fieldName|escape:'htmlall':'UTF-8'}:</strong>
          <span>{$fieldValue|escape:'htmlall':'UTF-8'}</span>
        </li>
      {/foreach}
    </ul>
  {/if}
</section>

