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
        
        $admin = new User();

        $admin->setRoles(["ROLE_ADMIN"])
        ->setPassword($this->hasher->getPasswordHasher($admin)->hash("admin"))
        ->setUsername("Kévin Gay");

        $manager->persist($admin);
        array_push($users, $admin);

        ///////////////////////////////

        $recruiter = new User();

        $recruiter->setRoles(["ROLE_ADMIN"])
        ->setPassword($this->hasher->getPasswordHasher($recruiter)->hash("recruteur"))
        ->setUsername("Recruteur");

        $manager->persist($recruiter);
        array_push($users, $recruiter);

        for($i = 0 ; $i < 18 ; $i++)
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

            $task->setName($this->faker->word())
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

            if($task->getTreatedAt() && rand() % 2)
            {
                $task->setFinalizedAt($this->faker->dateTimeBetween($task->getTreatedAt(), '+1 hour'));
            }
            else
            {
                $task->setFinalizedAt(null);
            }

            $manager->persist($task);
        }

        $manager->flush();
    }
}
