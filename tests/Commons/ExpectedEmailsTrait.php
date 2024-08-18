<?php

namespace App\Tests\Commons;

use App\Model\Contact\EmailDTO;

trait ExpectedEmailsTrait
{
    private const EMAIL_BODY = "one\nmessage\rto\r\nfix".
    "<script>alert('with a malicious script')</script>".
    "<style>body{color:red;}</style>";

    private const SANITIZED_EMAIL_BODY = "one\r\nmessage\r\nto\r\nfix";

    private function newEmail(): EmailDTO
    {
        $emailDTO = new EmailDTO();
        $emailDTO->setFirstName('John')
            ->setLastName('Doe')
            ->setEmailAddress('johndoe@example.com')
            ->setSubject('Test Subject')
            ->setMessage(self::EMAIL_BODY);

        return $emailDTO;
    }

    private function newEmailAsJSONArray(): array
    {
        $newEmail = $this->newEmail();

        return [
            'first_name' => $newEmail->getFirstName(),
            'last_name' => $newEmail->getLastName(),
            'email_address' => $newEmail->getEmailAddress(),
            'subject' => $newEmail->getSubject(),
            'message' => $newEmail->getMessage(),
        ];
    }

    private function getExpectedHeaders(EmailDTO $emailDTO): array
    {
        return [
            'From' => \sprintf(
                '%s %s <%s>',
                $emailDTO->getFirstName(),
                $emailDTO->getLastName(),
                $emailDTO->getEmailAddress()
            ),
            'Reply-To' => $emailDTO->getEmailAddress(),
        ];
    }
}
