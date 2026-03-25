<div class="demoextrafield demoextrafield--cart">
  <strong>{$demoExtraFieldTitle|escape:'htmlall':'UTF-8'}</strong>

  {if empty($moduleExtras)}
    <div><em>{l s='No extra fields found for this module.' d='Modules.Demoextrafield.Admin'}</em></div>
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
</div>

