<?php

namespace App\DataFixtures\Release;

use App\Entity\Release\ReleaseCreditType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReleaseCreditTypeFixtures extends Fixture
{
    public const COMPOSER_CREDIT_TYPE = 'composer-credit-type';
    public const PRODUCER_CREDIT_TYPE = 'producer-credit-type';
    public const LYRICIST_CREDIT_TYPE = 'lyricist-credit-type';
    public const EDITOR_CREDIT_TYPE = 'editor-credit-type';
    public const VIOLINIST_CREDIT_TYPE = 'violinist-credit-type';
    public const VOICE_CREDIT_TYPE = 'voice-credit-type';

    public function load(ObjectManager $manager): void
    {
        $composerType = new ReleaseCreditType();
        $composerType->setCreditName('composer');

        $producerType = new ReleaseCreditType();
        $producerType->setCreditName('producer');

        $lyricistType = new ReleaseCreditType();
        $lyricistType->setCreditName('lyricist');

        $editorType = new ReleaseCreditType();
        $editorType->setCreditName('editor');

        $violinistType = new ReleaseCreditType();
        $violinistType->setCreditName('violinist');

        $voiceType = new ReleaseCreditType();
        $voiceType->setCreditName('voice');

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
