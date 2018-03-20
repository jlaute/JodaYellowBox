{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_page_wrap"}
    {$smarty.block.parent}

    <div class="yellow-box" data-yellow-box="true">
        {block name="frontend_yellow_box_inner"}
            <div class="yellow-box--inner">
                <div class="box--content">
                    {block name="frontend_yellow_box_content"}
                        <ul class="list">
                            {block name="frontend_yellow_box_content_list"}
                                <li class="list--entry">number - name - description</li>
                            {/block}
                        </ul>
                    {/block}
                </div>
            </div>
        {/block}
    </div>
{/block}
