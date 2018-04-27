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
                        {if $currentRelease}
                            <li class="list--entry entry--header">{s name="ticket_list_headline"}{/s}</li>
                            {foreach $currentRelease->getTickets() as $ticket}
                                <li class="list--entry" data-ticket-id="{$ticket->getId()}">
                                    <div class="entry--actions">
                                        {foreach $ticket->getPossibleTransitions() as $transition}
                                            {assign var="snippetName" value="transition_{$transition}"}
                                            <button class="btn {$transition}" data-ticket-transition="{$transition}" title="{$transition|snippet:$snippetName:"frontend/yellow_box/index"}">
                                                <i class="icon--transition-{$transition}"></i>
                                            </button>
                                        {foreachelse}
                                            {if $ticket->getState() == 'approved'}
                                                <i class="icon--check"></i>
                                            {/if}
                                        {/foreach}
                                    </div>

                                    {if $ticket->getNumber()}{$ticket->getNumber()}{else}{$ticket@iteration}{/if} - {$ticket->getName()}
                                    {if $ticket->getState() == 'approved'}
                                        | Angenommen am: {$ticket->getChangedAt()|date_format}
                                    {/if}
                                </li>
                            {foreachelse}
                                <li>{s name="ticket_list_no_release_tickets"}{/s}</li>
                            {/foreach}
                        {else}
                            <li>{s name="ticket_list_no_release"}{/s}</li>
                        {/if}
                    {/block}
                </ul>
            {/block}
        </div>
    </div>
{/block}
