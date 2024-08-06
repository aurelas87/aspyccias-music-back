<?php

namespace App\DataFixtures\Release;

use App\Entity\Release\ReleaseCreditType;
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
        $composerType->setCreditName(self::COMPOSER_CREDIT_TYPE);

        $producerType = new ReleaseCreditType();
        $producerType->setCreditName(self::PRODUCER_CREDIT_TYPE);

        $lyricistType = new ReleaseCreditType();
        $lyricistType->setCreditName(self::LYRICIST_CREDIT_TYPE);

        $editorType = new ReleaseCreditType();
        $editorType->setCreditName(self::EDITOR_CREDIT_TYPE);

        $violinistType = new ReleaseCreditType();
        $violinistType->setCreditName(self::VIOLINIST_CREDIT_TYPE);

        $voiceType = new ReleaseCreditType();
        $voiceType->setCreditName(self::VOICE_CREDIT_TYPE);

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
