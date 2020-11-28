<?php

namespace App\Notification;

use App\Entity\ExtDatabase\Contact;
use Twig\Environment;

class ContactNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notifyEmail(Contact $contact): void
    {
        $message = (new \Swift_Message('Contact request'))
            ->setFrom('noreply@domain.com')
            ->setTo('contact@domain.com')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact,
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
