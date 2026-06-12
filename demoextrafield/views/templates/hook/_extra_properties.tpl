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

  Note on displayFront=false fields:
  Filtering is native — they never reach this template. Presenter lazy arrays ($product,
  $category…) are built with forFrontOffice: true, and ObjectModel bags ($customer->extra_properties)
  detect the front-office controller context automatically.
*}
<ul>
  {foreach from=$objectModel->extra_properties.demoextrafield key=fieldName item=fieldValue}
    <li>
      <strong>{$fieldName|escape:'htmlall':'UTF-8'}:</strong>
      <span>{$fieldValue|escape:'htmlall':'UTF-8'}</span>
    </li>
  {foreachelse}
    <li><em>{l s='No extra fields found for this module.' d='Modules.Demoextrafield.Main'}</em></li>
  {/foreach}
</ul>
