{block name="frontend_yellow_box_inner"}
    <div class="button btn close"><i class="icon--minus3"></i></div>
    <div class="yellow-box--inner">
        <div class="box--info box--{if $success}success{else}error{/if}">
            {if $success}
                <div class="info--success"></div>
            {else}
                <div class="info--error">{$error}</div>
            {/if}
        </div>

        <div id="confirmation">
            <div class="title">
                <p>{s name="confirmation_title"}{/s}</p>
            </div>
            <div class="actions">
                <button class="btn confirm-yes">Yes</button>
                <button class="btn confirm-no">No</button>
            </div>
        </div>

        <div class="box--content">
            {block name="frontend_yellow_box_content"}
                <ul class="list">
                    {block name="frontend_yellow_box_content_list"}
                        {if $releaseName}
                            <li class="list--entry entry--header">{s name="ticket_list_headline"}{/s}</li>
                        {/if}
                        {if $tickets}
                            {foreach $tickets as $ticket}
                                <li class="list--entry" data-ticket-id="{$ticket->getId()}">
                                    {* temp comment for comment box *}
                                    <div class="temp-comment">{strip}{$ticket->getComment()}{/strip}</div>
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
                                    <div class="entry--content">
                                        {if $ticket->getNumber()}{$ticket->getNumber()}{else}{$ticket@iteration}{/if} - <b>{$ticket->getName()}</b>
                                        {if $ticket->getState() == 'approved'}
                                            {s name="ticket_approved_at"}{/s} {$ticket->getChangedAt()|date_format:"%d.%m.%Y %H:%M"}
                                        {/if}
                                    </div>
                                </li>
                            {/foreach}
                        {else}
                            <li class="list--entry">{s name="ticket_list_no_tickets"}{/s}</li>
                        {/if}
                    {/block}
                </ul>
            {/block}
        </div>

        <div class="comment-box">
            {* Reject comment form *}
            <form class="comment--form" action="{url module="widgets" controller="YellowBox" action="transition"}">
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
    </div>
{/block}
