<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Story\DefaultProductsStory;
use App\Story\DefaultUserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DefaultProductsStory::load();
        DefaultUserStory::load();
        $company = new Company();
        $company->setEmail("user0@example.com");
        $company->setName('Richardson');
        $company->setPassword(password_hash('password', PASSWORD_DEFAULT));
        $manager->persist($company);
        $manager->flush();
    }
}
