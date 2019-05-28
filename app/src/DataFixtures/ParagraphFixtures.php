<?php
/**
 * Paragraph Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Paragraph;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ParagraphFixtures.
 *
 * @method createMany(int $int, string $string, \Closure $param)
 */
class ParagraphFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'paragraphs', function ($i) {
            $paragraph = new Paragraph();
            $paragraph->setSubheading($this->faker->text(200));
            $paragraph->setTextContent($this->faker->text(2000));
            $paragraph->setArticle($this->getRandomReference('articles'));
            $paragraph->setPicture($this->getRandomReference('pictures'));

            return $paragraph;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return array(
            ArticleFixtures::class,
            PictureFixtures::class,
        );
    }
}
