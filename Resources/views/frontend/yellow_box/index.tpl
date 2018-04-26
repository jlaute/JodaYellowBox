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
                        {foreach $currentRelease->getTickets() as $ticket}
                            <li class="list--entry" data-ticket-id="{$ticket->getId()}">
                                <div class="entry--actions">
                                    {foreach $ticket->getPossibleTransitions() as $transition}
                                        <button class="btn" data-ticket-transition="{$transition}" title="{$transition}">
                                            <i class="icon--transition-{$transition}"></i>
                                        </button>
                                    {/foreach}
                                </div>
                                Number: {$ticket->getNumber()}
                                - Name: {$ticket->getName()}
                                - Description: {$ticket->getDescription()}
                                - State: {$ticket->getState()}
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
