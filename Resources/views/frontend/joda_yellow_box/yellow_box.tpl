{block name="frontend_yellow_box_inner"}
    <div class="yellow-box--inner">
        <div class="box--info box--{if $success}success{else}error{/if}">
            {if $success}
                <div class="info--success"></div>
            {else}
                <div class="info--error">{$error}</div>
            {/if}
        </div>
        <div class="box--content">
            {block name="frontend_yellow_box_content"}
                <ul class="list">
                    {block name="frontend_yellow_box_content_list"}
                        {foreach $currentTickets as $ticket}
                            <li class="list--entry">
                                <div class="entry--actions">
                                    <!-- approve -->
                                    <button class="btn" data-ticket-transition="approve" data-ticket-id="{$ticket.id}">
                                        <i class="icon--check"></i>
                                    </button>
                                    <!-- reject -->
                                    <button class="btn" data-ticket-transition="reject" data-ticket-id="{$ticket.id}">
                                        <i class="icon--cross"></i>
                                    </button>
                                </div>
                                Number: {$ticket.number}
                                - Name: {$ticket.name}
                                - Description: {$ticket.description}
                                - State: {$ticket.state}
                            </li>
                        {foreachelse}
                            <li>No Tickets found</li>
                        {/foreach}
                    {/block}
                </ul>
            {/block}
        </div>
    </div>
{/block}
