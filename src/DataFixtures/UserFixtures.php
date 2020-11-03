<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $i = 0;
        foreach ($this->getUsersData() as [$email, $password]) {
            $user = new User();
            $user
                ->setEmail($email)
                ->setRoles([])
                ->setPassword($this->encoder->encodePassword($user, $password))
                ->setEmployee($this->getReference('employee_' . $i++));

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsersData()
    {
        return [
            ['pracownik@emp.pl', 'admin123'],
            ['asystent@emp.pl', 'admin123'],
            ['ksiegowy@emp.pl', 'admin123'],
        ];
    }

    public function getDependencies()
    {
        return [
            EmployeeFixtures::class,
        ];
    }
}
