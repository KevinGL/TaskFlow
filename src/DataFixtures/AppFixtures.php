<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private PasswordHasherFactoryInterface $hasher;
    private $faker;

    public function __construct(PasswordHasherFactoryInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker =  Factory::create();
    }
    
    public function load(ObjectManager $manager): void
    {
        $users = [];
        
        $user = new User();

        $user->setRoles(["ROLE_ADMIN"])
        ->setPassword($this->hasher->getPasswordHasher($user)->hash("admin"))
        ->setUsername("admin");

        $manager->persist($user);
        array_push($users, $user);

        for($i = 0 ; $i < 19 ; $i++)
        {
            $user = new User();
            
            $user->setRoles(["ROLE_USER"])
            ->setPassword($this->hasher->getPasswordHasher($user)->hash("1234"))
            ->setUsername($this->faker->name());

            $manager->persist($user);
            array_push($users, $user);
        }

        //////////////////////////////////////////////////

        for($i = 0 ; $i < 100 ; $i++)
        {
            $task = new Task();

            $task->setName($this->faker->sentence())
            ->setDescription($this->faker->paragraph())
            ->setCreatedAt($this->faker->dateTimeBetween('-6 months', '-1 month'))
            ->setToDoBefore($this->faker->dateTimeBetween("-3 months", '+3 months'))
            ->setUser($users[rand() % count($users)]);

            if(rand() % 2)
            {
                $task->setTreatedAt($this->faker->dateTimeBetween('-1 month', 'now'));
            }
            else
            {
                $task->setTreatedAt(null);
            }

            $manager->persist($task);
        }

        $manager->flush();
    }
}
