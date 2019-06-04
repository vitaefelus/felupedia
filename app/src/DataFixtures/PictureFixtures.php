<?php
/**
 * Picture Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PictureFixtures.
 *
 * @method createMany(int $int, string $string, \Closure $param)
 */
class PictureFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'pictures', function ($i) {
            $picture = new Picture();
            $pictureValue = 0 === $i % 2 ? 'fixture.jpg' : '';
            $picture->setSrc($pictureValue);
            $picture->setDescription($this->faker->sentence);

            return $picture;
        });

        $manager->flush();
    }
}
