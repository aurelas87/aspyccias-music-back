<?php

namespace App\Model\Release;

use App\Validator\Release\ReleaseLinkName;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseLinkDTO
{
    public function __construct(
        #[Assert\Choice(callback: [ReleaseLinkCategory::class, 'enumNames'])]
        public string $category = '',

        #[Assert\NotBlank]
        #[ReleaseLinkName]
        public string $name = '',

        #[Assert\When(
            expression: 'this.category !== enum("App\\\Model\\\Release\\\ReleaseLinkCategory::smart_link")',
            constraints: [
                new Assert\Length(min: 18, max: 255),
                new Assert\Url(),
            ]
        )]
        public ?string $link = null,

        #[Assert\When(
            expression: 'this.category === enum("App\\\Model\\\Release\\\ReleaseLinkCategory::smart_link")',
            constraints: [
                new Assert\NotBlank(),
            ]
        )]
        public ?string $embedded = null,
    ) {
    }
}
