<?php

declare(strict_types=1);

namespace App\Controller\Contact;

use App\Model\Contact\EmailDTO;
use App\Service\Contact\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/contact')]
class ContactController extends AbstractController
{
    #[Route(path: '/email', name: 'app_contact_email', methods: ['POST'])]
    public function email(
        #[MapRequestPayload(acceptFormat: 'json')] EmailDTO $emailDTO,
        EmailService $emailService
    ): JsonResponse {
        $emailService->sendEmail($emailDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
