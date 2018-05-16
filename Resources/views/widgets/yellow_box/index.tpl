{* Yellow box init *}
<div class="yellow-box {if $snapPosition}snap--{$snapPosition}{/if} {if $isMinimized}minimized{/if}"
     data-yellow-box="true"
     data-transitionUrl="{url module="widgets" controller="YellowBox" action="transition"}"
>
    {include file="frontend/yellow_box/index.tpl"}
</div>
