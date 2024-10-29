<?php

namespace App\DataFixture;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProduceFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create and persist sample fruits
        $fruit1 = new Fruit();
        $fruit1->setName('Apple');
        $fruit1->setWeight(150);
        $manager->persist($fruit1);

        $fruit2 = new Fruit();
        $fruit2->setName('Banana');
        $fruit2->setWeight(120);
        $manager->persist($fruit2);

        $fruit3 = new Fruit();
        $fruit3->setName('Green Apple');
        $fruit3->setWeight(150);
        $manager->persist($fruit2);

        // Create and persist sample vegetables
        $vegetable1 = new Vegetable();
        $vegetable1->setName('Carrot');
        $vegetable1->setWeight(80);
        $manager->persist($vegetable1);

        $vegetable2 = new Vegetable();
        $vegetable2->setName('Tomato');
        $vegetable2->setWeight(100);
        $manager->persist($vegetable2);

        // Flush the changes to the database
        $manager->flush();  
    }
}
