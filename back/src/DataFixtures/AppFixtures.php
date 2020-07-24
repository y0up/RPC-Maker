<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\Provider\RpcMakerProvider;
use App\Entity\Avatar;
use App\Entity\Category;
use App\Entity\Command;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new RpcMakerProvider($faker));

        $categoryList = [];
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->categoryName);
            $manager->persist($category);
            $categoryList[] = $category;
        }

        $itemList = [];

        for ($i = 0; $i < 50; $i++) {
            $item = new Item();
            $randomCategoryObject = $faker->randomElement($categoryList);
            $randomCategory = $randomCategoryObject->getName();

            if ($randomCategory == 'Processeur') {
                $item->setName(
                    $faker->processorBrand 
                    . ' ' 
                    . $faker->randomElement([1, 3, 5, 7]) 
                    . ' ' 
                    . $faker->numberBetween(10, 99) . '00'
                );
            }
            else if($randomCategory == 'Carte mère'){
                $item->setName(
                    $faker->motherboardBrand 
                    . ' ' 
                    . $faker->randomLetter() 
                    . $faker->numberBetween(10, 99) 
                    . '00' 
                );
            } 
            else if($randomCategory == 'Carte graphique'){
                $item->setName(
                    $faker->gpuBrand 
                    . ' ' 
                    . $faker->graphicCardBrand 
                    . ' ' 
                    .$faker->numberBetween(10, 99) 
                    . '00'
                );
            }
            else if($randomCategory == 'RAM'){
                $item->setName(
                    $faker->ramBrand 
                    . ' ' 
                    . $faker->randomElement([2, 4, 8, 16, 32, 64])
                    . ' GO DDR' 
                    .  $faker->numberBetween(2, 4)
                );
            } 
            else if($randomCategory == 'Ventirad / Watercooling'){
                $item->setName(
                    $faker->ventiradBrand 
                    . ' ' 
                    . $faker->word()
                    . ' '
                    . $faker->numberBetween(1, 10)
                );
            } 
            else if($randomCategory == 'Carte son'){
                $item->setName(
                    $faker->soundCardBrand 
                    . ' '
                    . $faker->word()
                    . ' '
                    . $faker->randomDigitNotNull()
                );
            } 
            else if($randomCategory == 'Carte wifi'){
                $item->setName(
                    $faker->networkCardBrand 
                    . ' ' 
                    . $faker->randomLetter() 
                    . $faker->randomLetter() 
                    . $faker->numberBetween(10, 99)
                );
            } 
            else if($randomCategory == 'Boitier'){
                $item->setName(
                    $faker->caseBrand 
                    .  ' ' 
                    . $faker->randomLetter() 
                    . $faker->randomLetter() 
                    . $faker->numberBetween(10, 99)
                );
            } 
            else if($randomCategory == 'Alimentation'){
                $item->setName(
                    $faker->powerSupplyUnitBrand 
                    . ' ' 
                    . $faker->numberBetween(1000, 10000) 
                    . ' '
                    . $faker->randomElement([400, 450, 500, 550, 600, 650, 700, 750, 800, 850, 950]) 
                    . 'W'
                );
            } 
            else if($randomCategory == 'HDDs'){
                $item->setName(
                    $faker->hddsBrand 
                    . ' ' 
                    . $faker->randomElement([1, 2, 4, 8, 16, 32]) 
                    . 'To'
                );
            }
            else if($randomCategory == 'SSDs'){
                $item->setName(
                    $faker->ssdsBrand 
                    . $faker->randomElement([120, 250, 500, 750, 480, 240]) 
                    . 'Go'
                );
            }
            else if($randomCategory == 'Clavier'){
                $item->setName(
                    $faker->keyBoardBrand 
                    . ' ' 
                    . $faker->randomLetter() 
                    . $faker->randomDigitNotNull() 
                    . '0'
                );
            } 
            else if($randomCategory == 'Souris'){
                $item->setName(
                    $faker->mouseBrand 
                    . ' ' 
                    . $faker->randomLetter() 
                    . $faker->randomDigitNotNull() 
                    . $faker->randomDigitNotNull() 
                    . '0'
                );
            } 
            else if($randomCategory == 'Tapis'){
                $item->setName(
                    $faker->mousePadBrand 
                    . ' ' 
                    . $faker->numberBetween(10, 50) 
                    . ' cm x ' 
                    . $faker->numberBetween(10, 50) 
                    . ' cm'
                );
            } 
            else if($randomCategory == 'Micro-Casque'){
                $item->setName(
                    $faker->headphoneBrand 
                    . $faker->randomElement([5, 7]) 
                    . '1 ' 
                    . $faker->randomElement(['USB', 'JACK']) 
                    . ' ' 
                    . $faker->randomElement(['Micro', '']) 
                );
            } 
            else if($randomCategory == 'Enceintes'){
                $item->setName(
                    $faker->speakerBrand 
                    . ' ' 
                    . $faker->randomLetter() 
                    . $faker->randomDigitNotNull() 
                    . $faker->randomDigitNotNull() 
                    . '0'
                );
            } 
            else if($randomCategory == 'Webcam'){
                $item->setName(
                    $faker->webcamBrand 
                    . $faker->randomElement(['720p', '1080p']) 
                    . ' ' 
                    . $faker->randomElement(['Micro', ''])
                );
            }
            else if($randomCategory == 'Ecran'){
                $item->setName(
                    $faker->computerMonitorBrand 
                    . ' '
                    . $faker->randomElement([13, 15, 19, 17, 20, 21, 22, 23, 24, 26, 27, 30])
                    . '" '
                    . $faker->randomLetter()
                    . $faker->numberBetween(100, 999)
                    . $faker->randomLetter()
                    . $faker->randomLetter()
                );
            }
            else if($randomCategory == 'Imprimante'){
                $item->setName(
                    $faker->printerBrand
                    . ' '
                    . $faker->randomLetter()
                    . $faker->randomLetter()
                    . $faker->numberBetween(1000, 9999)
                    . $faker->randomLetter()
                );
            }
            else if($randomCategory == 'Systeme d\'exploitation'){
                $item->setName(
                    $faker->operatingSystemBrand
                );
            }
            else if($randomCategory == 'Lecteur / Graveur'){
                $item->setName(
                    $faker->cdPlayerBrand
                    . ' '
                    . $faker->randomLetter()
                    . $faker->numberBetween(100, 999)
                    . $faker->randomLetter()
                    . $faker->randomLetter()
                );
            }

            $item->setCategory($randomCategoryObject);
            $item->setPrice($faker->numberBetween(50, 500));
            $item->setUrl($faker->url());
            $manager->persist($item);
        }

        $avatarList = [];
        for($i= 0; $i < 25; $i++){
            $avatar = new Avatar;
            $avatar->setImage($faker->sentence);
            $manager->persist($avatar);
            $avatarList[] = $avatar;
        }

        $userList = [];
        for($i= 0; $i < 25; $i++){
            $user = new User;
            $user->setUsername($faker->randomUsername);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setLevel($faker->randomDigitNotNull);
            $user->setRole(['ROLE_USER']);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setAvatar($faker->unique->randomElement($avatarList));
            $manager->persist($user);
            $userList[] = $user;
        }
        
        for($i= 0; $i < 25; $i++){
            $command = new Command;
            $command->setStatus($faker->numberBetween(1, 5));
            $command->setData(['Data' => 'Oui', 'Non']);
            $command->setUser($faker->randomElement($userList));
            $manager->persist($command);
        }
        
        $manager->flush();
    }
}