<?php

namespace App\Model\Contact;

use Symfony\Component\Validator\Constraints as Assert;

readonly class EmailDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $firstName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $lastName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Email]
        public string $emailAddress,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $subject,

        #[Assert\NotBlank]
        #[Assert\Length(max: 5000)]
        public string $message
    ) {
    }
}
