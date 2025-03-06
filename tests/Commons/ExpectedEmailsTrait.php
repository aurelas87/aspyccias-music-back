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
        return new EmailDTO(
            'John',
            'Doe',
            'johndoe@example.com',
            'Test Subject',
            self::EMAIL_BODY
        );
    }

    private function newEmailAsJSONArray(): array
    {
        $newEmail = $this->newEmail();

        return [
            'first_name' => $newEmail->firstName,
            'last_name' => $newEmail->lastName,
            'email_address' => $newEmail->emailAddress,
            'subject' => $newEmail->subject,
            'message' => $newEmail->message,
        ];
    }

    private function getExpectedHeaders(EmailDTO $emailDTO): array
    {
        return [
            'From' => \sprintf(
                '%s %s <%s>',
                $emailDTO->firstName,
                $emailDTO->lastName,
                $emailDTO->emailAddress
            ),
            'Reply-To' => $emailDTO->emailAddress,
        ];
    }
}
