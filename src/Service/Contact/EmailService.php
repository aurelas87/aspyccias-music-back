<?php

namespace App\Service\Contact;

use App\Exception\Contact\EmailDeliveryException;
use App\Helper\EmailSender;
use App\Model\Contact\EmailDTO;

class EmailService
{
    private EmailSender $emailSender;
    private string $aspycciasEmail;

    public function __construct(EmailSender $emailSender, string $aspycciasEmail)
    {
        $this->emailSender = $emailSender;
        $this->aspycciasEmail = $aspycciasEmail;
    }

    public function sendEmail(EmailDTO $emailDTO): void
    {
        try {
            $this->emailSender->prepareEmail($emailDTO);
            $acceptedForDelivery = $this->emailSender->sendEmail($this->aspycciasEmail);
        } catch (\Throwable) {
            throw new EmailDeliveryException();
        }

        if (!$acceptedForDelivery) {
            throw new EmailDeliveryException();
        }
    }
}
