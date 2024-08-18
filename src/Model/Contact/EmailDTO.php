<?php

namespace App\Model\Contact;

use Symfony\Component\Validator\Constraints as Assert;

class EmailDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $lastName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\Email]
    private string $emailAddress;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $subject;

    #[Assert\NotBlank]
    #[Assert\Length(max: 5000)]
    private string $message;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): EmailDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): EmailDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): EmailDTO
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): EmailDTO
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): EmailDTO
    {
        $this->message = $message;

        return $this;
    }
}
