{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_header_javascript_data"}
    {$smarty.block.parent}

    {$controllerData['joda_yellow_box_index'] = {url controller="YellowBox" fullPath}}
    {$controllerData['joda_yellow_box_transition'] = {url controller="YellowBox" action="transition" fullPath}}
{/block}

{block name="frontend_index_page_wrap"}
    {$smarty.block.parent}

    {* Yellow box init *}
    <div class="yellow-box" data-yellow-box="true"></div>
{/block}
