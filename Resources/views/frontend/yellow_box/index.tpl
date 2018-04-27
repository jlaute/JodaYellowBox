{block name="frontend_yellow_box_inner"}
    <div class="button btn close"><i class="icon--cross"></i></div>
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
                                            <button class="btn {$transition}" data-ticket-transition="{$transition}" title="{$transition|snippet:$snippetName:"frontend/yellow_box/index"}" data-ticket-name="{$ticket->getName()}">
                                                <i class="icon--transition-{$transition}"></i>
                                            </button>
                                        {foreachelse}
                                            {if $ticket->getState() == 'approved'}
                                                <i class="icon--check"></i>
                                            {/if}
                                        {/foreach}
                                    </div>
                                    <div class="existing-comment" hidden>{$ticket->getComment()}</div>
                                    {if $ticket->getNumber()}{$ticket->getNumber()}{else}{$ticket@iteration}{/if} - <b>{$ticket->getName()}</b>
                                    {if $ticket->getState() == 'approved'}
                                        {s name="ticket_approved_at"}{/s} {$ticket->getChangedAt()|date_format:"%d.%m.%Y %H:%M"}
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
        <form class="comment-form" action="{url controller="YellowBox" action="transition"}" hidden>
            <div>{s name="comment_header"}{/s}</div><br />
            <input type="text" name="ticketId" value="" hidden/>
            <input type="text" name="ticketTransition" value="" hidden/>
            <textarea name="comment" cols="30" rows="5"></textarea>
            <button class="btn abort" type="button">{s name="comment_cancel"}{/s}</button>
            <button class="btn btn-primary" type="submit">{s name="comment_send"}{/s}</button>
        </form>
    </div>
{/block}
