<?php
/**
 * Comment Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CommentFixtures.
 *
 * @method createMany(int $int, string $string, \Closure $param)
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'comments', function ($i) {
            $comment = new Comment();
            $comment->setContent($this->faker->text);
            $comment->setIsVisible($this->faker->boolean);
            $comment->setArticle($this->getRandomReference('articles'));
            $comment->setAuthor($this->getRandomReference('admins'));

            return $comment;
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
        return [
            ArticleFixtures::class,
            UserFixtures::class,
        ];
    }
}
