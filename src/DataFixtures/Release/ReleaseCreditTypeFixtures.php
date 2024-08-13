<?php

namespace App\DataFixtures\Release;

use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseCreditTypeTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReleaseCreditTypeFixtures extends Fixture
{
    public const COMPOSER_CREDIT_TYPE = 'composer';
    public const PRODUCER_CREDIT_TYPE = 'producer';
    public const LYRICIST_CREDIT_TYPE = 'lyricist';
    public const EDITOR_CREDIT_TYPE = 'editor';
    public const VIOLINIST_CREDIT_TYPE = 'violinist';
    public const VOICE_CREDIT_TYPE = 'voice';

    public function load(ObjectManager $manager): void
    {
        $composerType = new ReleaseCreditType();
        $composerType->setCreditNameKey(self::COMPOSER_CREDIT_TYPE);
        $composerType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Composer')
        );
        $composerType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Compositeur')
        );

        $producerType = new ReleaseCreditType();
        $producerType->setCreditNameKey(self::PRODUCER_CREDIT_TYPE);
        $producerType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Producer')
        );
        $producerType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Producteur')
        );

        $lyricistType = new ReleaseCreditType();
        $lyricistType->setCreditNameKey(self::LYRICIST_CREDIT_TYPE);
        $lyricistType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Lyricist')
        );
        $lyricistType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Parolier')
        );

        $editorType = new ReleaseCreditType();
        $editorType->setCreditNameKey(self::EDITOR_CREDIT_TYPE);
        $editorType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Editor')
        );
        $editorType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Éditeur')
        );

        $violinistType = new ReleaseCreditType();
        $violinistType->setCreditNameKey(self::VIOLINIST_CREDIT_TYPE);
        $violinistType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Violinist')
        );
        $violinistType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Violoniste')
        );

        $voiceType = new ReleaseCreditType();
        $voiceType->setCreditNameKey(self::VOICE_CREDIT_TYPE);
        $voiceType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('en')
                ->setCreditName('Voice')
        );
        $voiceType->addTranslation(
            (new ReleaseCreditTypeTranslation())
                ->setLocale('fr')
                ->setCreditName('Chant')
        );

        $manager->persist($composerType);
        $manager->persist($producerType);
        $manager->persist($lyricistType);
        $manager->persist($editorType);
        $manager->persist($violinistType);
        $manager->persist($voiceType);

        $manager->flush();

        $this->addReference(self::COMPOSER_CREDIT_TYPE, $composerType);
        $this->addReference(self::PRODUCER_CREDIT_TYPE, $producerType);
        $this->addReference(self::LYRICIST_CREDIT_TYPE, $lyricistType);
        $this->addReference(self::EDITOR_CREDIT_TYPE, $editorType);
        $this->addReference(self::VIOLINIST_CREDIT_TYPE, $violinistType);
        $this->addReference(self::VOICE_CREDIT_TYPE, $voiceType);
    }
}
