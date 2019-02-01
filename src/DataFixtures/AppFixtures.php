<?php
/**
 * Created by PhpStorm.
 * User: Experience
 * Date: 15/07/2018
 * Time: 19:16
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
         $goodplan = new \App\Entity\Category();
         $goodplan->setLibelle("goodplan");
         $pratical = new \App\Entity\Category();
         $pratical->setLibelle("pratical");

         $this->addReference('page1', $goodplan);
         $manager->persist($goodplan);
         $this->addReference('page2', $pratical);
         $manager->persist($pratical);

        foreach ($this->getUserData() as [$username, $password, $email ,$type ,$phone ,$roles]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setType($type);
            $user->setPhone($phone);
            $user->setRoles($roles);
            $user->setPhoto("image.jpeg");

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$username, $password, $email, $type,$phone,$roles];
            [ 'president_admin', 'kitten', 'metas.belfort@gmail.com','president','0661569987', ['ROLE_ADMIN']],
            [ 'secretary_admin', 'kitten', 'mail@gmail.com','secretary','0661569974', ['ROLE_USER']],
            [ 'treasury_admin', 'kitten', 'treasure@gmail.com','treasury','0661569969', ['ROLE_USER']],
            [ 'Paul Martin', 'kitten', 'paul@gmail.com','communication','0661569477', ['ROLE_USER']],
            [ 'Benedicte Grâce', 'kitten', 'benedicte@gmail.com','activity','0661569987', ['ROLE_USER']],
            [ 'Kante Miel', 'kitten', 'kante@gmail.com','sport','0661569987', ['ROLE_USER']],
            [ 'Marie', 'kitten', 'metas.belfort@gmail.com','member','0661569987', ['ROLE_USER']],
            [ 'Leslie', 'kitten', 'metas.belfort@gmail.com','member','0661569987', ['ROLE_USER']],
            [ 'Marcial', 'kitten', 'tom_admin@symfony.com','member','0661567787', ['ROLE_USER']],
            [ 'Michel_user', 'kitten', 'john_user@symfony.com','member','0661569487', ['ROLE_USER']],
        ];
    }
}