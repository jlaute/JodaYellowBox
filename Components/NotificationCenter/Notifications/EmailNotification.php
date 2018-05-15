<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\NotificationCenter\Notifications;

use JodaYellowBox\Exception\NotificationException;

class EmailNotification implements NotificationInterface
{
    /**
     * @var bool
     */
    protected $sent = false;

    /**
     * @var \Enlight_Components_Mail
     */
    private $mail;

    /**
     * @var array
     */
    private $to = [];

    /**
     * @param \Enlight_Components_Mail $mail
     * @param array                    $to
     */
    public function __construct(\Enlight_Components_Mail $mail, array $to = [])
    {
        $this->mail = $mail;
        $this->to = $to;
    }

    /**
     * Sends the notification email
     *
     * @param string $message
     *
     * @throws NotificationException
     * @throws \Zend_Mail_Exception
     *
     * @return bool
     */
    public function send(string $message)
    {
        $this->mail->addTo($this->to);
        $this->mail->setFrom('developer@isento-ecommerce.de', 'Yellow Box');
        $this->mail->setSubject('Email-Notification');
        $this->mail->setBodyText($message);

        try {
            $this->mail->send();
        } catch (\Exception $ex) {
            throw new NotificationException('Error while notification');
        }

        return true;
    }
}
