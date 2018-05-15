<?php

namespace spec\JodaYellowBox\Components\NotificationCenter\Notifications;

use JodaYellowBox\Components\NotificationCenter\Notifications\EmailNotification;
use PhpSpec\ObjectBehavior;

/**
 * @package spec\JodaYellowBox\Components\NotificationCenter\Notifications
 * @mixin EmailNotification
 */
class EmailNotificationSpec extends ObjectBehavior
{
    function let (\Enlight_Components_Mail $mail)
    {
        $to = [
            'test@joda.de',
            'test1@joda.de'
        ];

        $this->beConstructedWith($mail, $to);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmailNotification::class);
    }

    function it_is_able_to_send_an_email()
    {
        $this->send('A notification')->shouldReturn(true);
    }

    function it_cant_send_an_email_with_error(\Enlight_Components_Mail $mail)
    {
        $mail->send()->willThrow(\Exception::class);
        $this->shouldThrow(\Exception::class)->duringSend('A notification');
    }
}
