<?php

namespace App\Service;

use App\Entity\News\News;
use App\Entity\Profile\Profile;
use App\Entity\Profile\ProfileLink;
use App\Entity\Release\Release;
use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseLinkName;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class EntitySanitizer
{
    private HtmlSanitizerInterface $htmlSanitizer;

    public function __construct(HtmlSanitizerInterface $htmlSanitizer)
    {
        $this->htmlSanitizer = $htmlSanitizer;
    }

    public function sanitizeEntity($entity): void
    {
        $className = \get_class($entity);

        switch ($className) {
            case Profile::class:
                /** @var Profile $entity */
                $entity->setWelcome($this->sanitizeAndKeepHTMLEntities($entity->getWelcome()));
                $entity->setDescription($this->sanitizeAndKeepHTMLEntities($entity->getDescription()));
                break;

            case ProfileLink::class:
                /** @var ProfileLink $entity */
                $entity->setName($this->sanitizeAndKeepHTMLEntities($entity->getName()));
                $entity->setLink($this->sanitizeAndKeepHTMLEntities($entity->getLink()));
                break;

            case News::class:
                /** @var News $entity */
                $entity->setSlug($this->sanitizeAndKeepHTMLEntities($entity->getSlug()));

                foreach ($entity->getTranslations() as $newsTranslations) {
                    $newsTranslations->setTitle($this->sanitizeAndKeepHTMLEntities($newsTranslations->getTitle()));
                    $newsTranslations->setContent($this->sanitizeAndKeepHTMLEntities($newsTranslations->getContent()));
                }
                break;

            case ReleaseCreditType::class:
                /** @var ReleaseCreditType $entity */
                $entity->setCreditNameKey($this->sanitizeAndKeepHTMLEntities($entity->getCreditNameKey()));

                foreach ($entity->getTranslations() as $releaseCreditTypeTranslations) {
                    $releaseCreditTypeTranslations->setCreditName($this->sanitizeAndKeepHTMLEntities($releaseCreditTypeTranslations->getCreditName()));
                }
                break;

            case ReleaseLinkName::class:
                /** @var ReleaseLinkName $entity */
                $entity->setLinkName($this->sanitizeAndKeepHTMLEntities($entity->getLinkName()));
                break;

            case Release::class:
                /** @var Release $entity */
                $entity->setTitle($this->sanitizeAndKeepHTMLEntities($entity->getTitle()));
                $entity->setSlug($this->sanitizeAndKeepHTMLEntities($entity->getSlug()));

                foreach ($entity->getTranslations() as $releaseTranslations) {
                    $releaseTranslations->setDescription($this->sanitizeAndKeepHTMLEntities($releaseTranslations->getDescription()));
                }
                break;
        }
    }

    private function sanitizeAndKeepHTMLEntities(string $value): string
    {
        $value = $this->htmlSanitizer->sanitize($value);

        return \html_entity_decode($value);
    }
}
