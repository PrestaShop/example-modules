{foreach from=$contentBlockList item=contentBlock}
    <div style="background-color:#f6f6f6;padding:50px;margin-bottom:10px;">
        <H2>{$contentBlock.title}</H2>
        <p style="{if $color}color:{$color};{/if}{if $italic}font-style:italic;{/if}{if $bold}font-weight:bold;{/if}">
            {$contentBlock.description}
        </p>
    </div>
{/foreach}
