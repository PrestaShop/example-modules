<div class="panel">
    <div class="panel-heading">
        <i class="icon-link"></i> {l s='Demo routes' mod='demomoduleroutes'}
    </div>
    <div class="panel-body">
        <p>{l s='Below are the custom front-office URLs registered by this module:' mod='demomoduleroutes'}</p>
        <ul>
            <li>
                <strong>{l s='List controller:' mod='demomoduleroutes'}</strong>
                <a href="{$urlList|escape:'html':'UTF-8'}" target="_blank">{$urlList|escape:'html':'UTF-8'}</a>
            </li>
            <li>
                <strong>{l s='Show controller (example with id=1, slug=abc):' mod='demomoduleroutes'}</strong>
                <a href="{$urlShow|escape:'html':'UTF-8'}" target="_blank">{$urlShow|escape:'html':'UTF-8'}</a>
            </li>
        </ul>
    </div>
</div>
