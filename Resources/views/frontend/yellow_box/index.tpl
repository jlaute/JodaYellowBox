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

        {* Reject comment form *}
        <form class="comment-form" action="{url controller="YellowBox" action="transition"}" hidden>
            {block name="frontend_yellow_box_comment_form"}
                {block name="frontend_yellow_box_comment_form_hidden_inputs"}
                    <input type="hidden" name="ticketId" />
                    <input type="hidden" name="ticketTransition" />
                {/block}
                {block name="frontend_yellow_box_comment_form_head"}
                    <div class="comment--head">{s name="comment_header"}{/s}</div>
                {/block}
                {block name="frontend_yellow_box_comment_form_content"}
                    <textarea title="{s name="comment_description"}{/s}" name="comment" cols="30" rows="5">{s name="comment_placeholder"}{/s}</textarea>
                    <button class="btn abort" type="button">{s name="comment_cancel"}{/s}</button>
                    <button class="btn btn-primary submit" type="submit">{s name="comment_send"}{/s}</button>
                {/block}
            {/block}
        </form>
    </div>
{/block}
