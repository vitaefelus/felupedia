<?php
/**
 * Article fixture.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ArticleFixtures.
 *
 * @method createMany(int $int, string $string, \Closure $param)
 */
class ArticleFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'articles', function ($i) {
            $article = new Article();
            $article->setTitle($this->faker->word);
            $article->setIsAccepted($this->faker->boolean);

            return $article;
        });

        $manager->flush();
    }
}
