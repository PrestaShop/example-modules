{*
  Displays all extra fields registered by this module for a given entity.

  Usage: {include file='./_extra_properties.tpl' objectModel=$product}
  where $objectModel is a LazyArray (or any array) exposing extra_properties.{moduleName}.{fieldName}.

  Note on lang-scoped fields (scope="lang"):
  The ExtraPropertyReader translates the per-language array into a single scalar value for the
  current storefront language before returning it. No special handling is needed here.

  Note on displayFront=false fields:
  Fields registered with displayFront=false are filtered out by ExtraPropertiesLazyArray::getValues()
  before this template is reached. They will never appear here even if they exist in the database.
*}
{if empty($objectModel.extra_properties.demoextrafield)}
  <p><em>{l s='No extra fields found for this module.' d='Modules.Demoextrafield.Main'}</em></p>
{else}
  <ul>
    {foreach from=$objectModel.extra_properties.demoextrafield key=fieldName item=fieldValue}
      <li>
        <strong>{$fieldName|escape:'htmlall':'UTF-8'}:</strong>
        <span>{$fieldValue|escape:'htmlall':'UTF-8'}</span>
      </li>
    {/foreach}
  </ul>
{/if}
