<?php

namespace App\Test\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RecipeRepository $repository;
    private string $path = '/recipe/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Recipe::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recipe index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'recipe[name]' => 'Testing',
            'recipe[time]' => 'Testing',
            'recipe[people]' => 'Testing',
            'recipe[difficulty]' => 'Testing',
            'recipe[description]' => 'Testing',
            'recipe[price]' => 'Testing',
            'recipe[creationDate]' => 'Testing',
            'recipe[favorite]' => 'Testing',
            'recipe[updateDate]' => 'Testing',
            'recipe[ingredient]' => 'Testing',
        ]);

        self::assertResponseRedirects('/recipe/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setTime('My Title');
        $fixture->setPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPrice('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setFavorite('My Title');
        $fixture->setUpdateDate('My Title');
        $fixture->setIngredient('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recipe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setTime('My Title');
        $fixture->setPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPrice('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setFavorite('My Title');
        $fixture->setUpdateDate('My Title');
        $fixture->setIngredient('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'recipe[name]' => 'Something New',
            'recipe[time]' => 'Something New',
            'recipe[people]' => 'Something New',
            'recipe[difficulty]' => 'Something New',
            'recipe[description]' => 'Something New',
            'recipe[price]' => 'Something New',
            'recipe[creationDate]' => 'Something New',
            'recipe[favorite]' => 'Something New',
            'recipe[updateDate]' => 'Something New',
            'recipe[ingredient]' => 'Something New',
        ]);

        self::assertResponseRedirects('/recipe/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getTime());
        self::assertSame('Something New', $fixture[0]->getPeople());
        self::assertSame('Something New', $fixture[0]->getDifficulty());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getCreationDate());
        self::assertSame('Something New', $fixture[0]->getFavorite());
        self::assertSame('Something New', $fixture[0]->getUpdateDate());
        self::assertSame('Something New', $fixture[0]->getIngredient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setTime('My Title');
        $fixture->setPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setDescription('My Title');
        $fixture->setPrice('My Title');
        $fixture->setCreationDate('My Title');
        $fixture->setFavorite('My Title');
        $fixture->setUpdateDate('My Title');
        $fixture->setIngredient('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/recipe/');
    }
}
