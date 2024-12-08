<?php

namespace App\Service;

use App\Entity\News\News;
use App\Entity\Profile\Profile;
use App\Entity\Profile\ProfileLink;
use App\Entity\Release\Release;
use App\Entity\Release\ReleaseCredit;
use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseLink;
use App\Entity\Release\ReleaseLinkName;
use App\Entity\Release\ReleaseTrack;
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
                $entity->setName($this->htmlSanitizer->sanitize($entity->getName()));
                $entity->setLink($this->htmlSanitizer->sanitize($entity->getLink()));
                break;

            case News::class:
                /** @var News $entity */
                $entity->setSlug($this->htmlSanitizer->sanitize($entity->getSlug()));

                foreach ($entity->getTranslations() as $newsTranslations) {
                    $newsTranslations->setTitle($this->htmlSanitizer->sanitize($newsTranslations->getTitle()));
                    $newsTranslations->setContent($this->sanitizeAndKeepHTMLEntities($newsTranslations->getContent()));
                }
                break;

            case ReleaseCreditType::class:
                /** @var ReleaseCreditType $entity */
                $entity->setCreditNameKey($this->htmlSanitizer->sanitize($entity->getCreditNameKey()));

                foreach ($entity->getTranslations() as $releaseCreditTypeTranslations) {
                    $releaseCreditTypeTranslations->setCreditName($this->htmlSanitizer->sanitize($releaseCreditTypeTranslations->getCreditName()));
                }
                break;

            case ReleaseLinkName::class:
                /** @var ReleaseLinkName $entity */
                $entity->setLinkName($this->htmlSanitizer->sanitize($entity->getLinkName()));
                break;

            case Release::class:
                /** @var Release $entity */
                $entity->setTitle($this->htmlSanitizer->sanitize($entity->getTitle()));
                $entity->setSlug($this->htmlSanitizer->sanitize($entity->getSlug()));

                foreach ($entity->getTranslations() as $releaseTranslations) {
                    $releaseTranslations->setDescription($this->sanitizeAndKeepHTMLEntities($releaseTranslations->getDescription()));
                }
                break;

            case ReleaseTrack::class:
                /** @var ReleaseTrack $entity */
                $entity->setTitle($this->htmlSanitizer->sanitize($entity->getTitle()));
                break;

            case ReleaseCredit::class:
                /** @var ReleaseCredit $entity */
                $entity->setFullName($this->htmlSanitizer->sanitize($entity->getFullName()));

                if (\is_string($entity->getLink())) {
                    $entity->setLink($this->htmlSanitizer->sanitize($entity->getLink()));
                }
                break;

            case ReleaseLink::class:
                /** @var ReleaseLink $entity */
                if (\is_string($entity->getLink())) {
                    $entity->setLink($this->htmlSanitizer->sanitize($entity->getLink()));
                }

                // TODO: Filter embedded content properly, HtmlSanitizer is too much restrictive (try symfony/dom-crawler?)
//                if (\is_string($entity->getEmbedded())) {
//                    $entity->setEmbedded($this->sanitizeAndKeepHTMLEntities($entity->getEmbedded()));
//                }
        }
    }

    private function sanitizeAndKeepHTMLEntities(string $value): string
    {
        $value = $this->htmlSanitizer->sanitize($value);

        return \html_entity_decode($value);
    }
}
