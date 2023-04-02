<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private IngredientRepository $ingredientRepository,
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $em = $this->container->get('doctrine')->getEntityManager('default');
        // $ingredientRepository = $em->getRepository(IngredientRepository::class);

        for ($i=1; $i <= 50; $i++) { 
            $ingredient = new Ingredient();
            $ingredient
                ->setName($this->faker->unique()->word())
                ->setPrice($this->faker->randomFloat(2, 1, 100));

            $manager->persist($ingredient);
        }

        $manager->flush();

        for ($i=1; $i <= 50; $i++) {
            $recipe = new Recipe();
            $recipe
                ->setDescription($this->faker->realTextBetween())
                ->setDifficulty($this->faker->numberBetween(1, 5))
                ->setFavorite($this->faker->boolean(10))
                ->setName($this->faker->unique()->sentence(3))
                ->setPeople($this->faker->numberBetween(1, 50))
                ->setPrice($this->faker->randomFloat(2, 1, 1000))
                ->setTime($this->faker->numberBetween(1, 1440));

            $ingredients = $this->ingredientRepository->createQueryBuilder('i')
                ->orderBy('RAND()')
                ->setMaxResults(rand(1, 10))
                ->getQuery()
                ->getResult();

            foreach ($ingredients as $ingredient) {
                $recipe->addIngredient($ingredient);
            }
            
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
