<?php
/**
 * Article fixture.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ArticleFixtures.
 *
 * @method createMany(int $int, string $string, \Closure $param)
 */
class ArticleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(25, 'articles', function ($i) {
            $article = new Article();
            $article->setTitle($this->faker->sentence(2));
            $article->setIsAccepted($this->faker->boolean);

            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(0, 5)
            );
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }

            return $article;
        });

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return array(
            TagFixtures::class,
        );
    }
}
