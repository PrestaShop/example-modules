{*
  Displays all extra fields registered by this module for a given entity.

  Usage: {include file='./_extra_properties.tpl' objectModel=$product}
  where $objectModel is a LazyArray or a raw ObjectModel exposing extra_properties.{moduleName}.{fieldName}.
  The first hop uses `->` (works on both: LazyArray and ObjectModel resolve it via __get;
  ObjectModel is not ArrayAccess so dot syntax would fail on it). `extra_properties` is an
  ExtraPropertiesBag and each module entry a ModuleFieldsBag — both support dot syntax and iteration.

  Note on lang-scoped fields (scope="lang"):
  The ExtraPropertyReader translates the per-language array into a single scalar value for the
  current storefront language before returning it. No special handling is needed here.

  Note on json-typed fields (type="json"):
  Reads return the DECODED structure (a PHP array), not the raw JSON string — hence the
  is_array branch below, which iterates the first level and prints deeper levels re-encoded.

  Note on displayFront=false fields:
  Filtering is native — they never reach this template. Presenter lazy arrays ($product,
  $category…) are built with forFrontOffice: true, and ObjectModel bags ($customer->extra_properties)
  detect the front-office controller context automatically.
*}
<ul>
  {foreach from=$objectModel->extra_properties.demoextrafield key=fieldName item=fieldValue}
    <li>
      <strong>{$fieldName|escape:'htmlall':'UTF-8'}:</strong>
      {if is_array($fieldValue)}
        {* JSON field: the decoded structure is iterable as-is. *}
        <ul>
          {foreach from=$fieldValue key=jsonKey item=jsonValue}
            <li>
              <em>{$jsonKey|escape:'htmlall':'UTF-8'}:</em>
              {if is_array($jsonValue)}
                <span>{$jsonValue|json_encode|escape:'htmlall':'UTF-8'}</span>
              {else}
                <span>{$jsonValue|escape:'htmlall':'UTF-8'}</span>
              {/if}
            </li>
          {/foreach}
        </ul>
      {else}
        <span>{$fieldValue|escape:'htmlall':'UTF-8'}</span>
      {/if}
    </li>
  {foreachelse}
    <li><em>{l s='No extra fields found for this module.' d='Modules.Demoextrafield.Main'}</em></li>
  {/foreach}
</ul>
