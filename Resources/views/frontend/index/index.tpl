{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_page_wrap"}
    {$smarty.block.parent}

    {action module="widgets" controller="YellowBox" action="index"}
{/block}
