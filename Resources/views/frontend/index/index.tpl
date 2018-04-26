{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_page_wrap"}
    {$smarty.block.parent}

    {* Yellow box init *}
    <div class="yellow-box"
         data-yellow-box="true"
         data-transitionUrl="{url controller="YellowBox" action="transition"}"
    >
        {include file="frontend/yellow_box/index.tpl"}
    </div>
{/block}
