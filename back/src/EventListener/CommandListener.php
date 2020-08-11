<?php

namespace App\EventListener;

use App\Entity\Command;
use App\Entity\Message;
use App\Repository\ItemRepository;
use App\Service\MySlugger;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CommandListener
{
    private $slugger;
    private $userRepo;
    private $em;
    private $itemRepo;

    public function __construct(MySlugger $slugger, UserRepository $userRepo,ItemRepository $itemRepo, EntityManagerInterface $em)
    {
        $this->slugger = $slugger;
        $this->userRepo = $userRepo;
        $this->em = $em;
        $this->itemRepo = $itemRepo;
    }

    /**
     *  Sluggify the name of Command on creation or update
     *
     * @param Command $command Entity of Command
     * @param LifecycleEventArgs $event
     * @return void
     */
    public function sluggify(Command $command, LifecycleEventArgs $event)
    {
        $command->setSlug($this->slugger->slugify($command->getName()));
    }

    /**
     * Change the level of the User when the status of the command is updated
     *
     * @param Command $command Entity of Command
     * @param LifecycleEventArgs $event
     * @return void
     */
    public function levelChange(Command $command, LifecycleEventArgs $event)
    {
        // getting the user related to the command
        $user = $command->getUser();
        //getting all the status of all the command this user have
        $commandsStatus = [];
        $commands = $this->userRepo->find($user)->getCommands();
        foreach($commands as $command){
            $commandsStatus[] = $command->getStatus();
        }
        // getting the sum of all the status to create the level of the user
        $user->setLevel(array_sum($commandsStatus));
        // persist it in the database
        $this->em->persist($user);
        $this->em->flush();
    }

    public function setItemsOnUserCreation(Command $command, LifecycleEventArgs $event)
    {
        $data = $command->getCommandData();
        $configData = $data->getCommandConfigData();
        $specData = $data->getCommandSpecData();
        $deviceData = $data->getCommandDeviceData();

        if($configData->getConfigProc()){
            $proc = $this->itemRepo->findBy(['name' => $configData->getConfigProc()]);
            $command->addItem($proc[0]);
        }

        if($configData->getConfigBoard()){
            $board = $this->itemRepo->findBy(['name' => $configData->getConfigBoard()]);
            $command->addItem($board[0]);
        }

        if($configData->getConfigGc()){
            $gc = $this->itemRepo->findBy(['name' => $configData->getConfigGc()]);
            $command->addItem($gc[0]);
        }

        if($configData->getConfigRam()){
            $ram = $this->itemRepo->findBy(['name' => $configData->getConfigRam()]);
            $command->addItem($ram[0]);
        }

        if($configData->getConfigRefresh()){
            $refresh = $this->itemRepo->findBy(['name' => $configData->getConfigRefresh()]);
            $command->addItem($refresh[0]);
        }

        if($configData->getConfigStorage()){
            $storage = $this->itemRepo->findBy(['name' => $configData->getConfigStorage()]);
            $command->addItem($storage[0]);
        }

        if($configData->getConfigBoardsound()){
            $boardsound = $this->itemRepo->findBy(['name' => $configData->getConfigBoardsound()]);
            $command->addItem($boardsound[0]);
        }

        if($configData->getConfigCase()){
            $case = $this->itemRepo->findBy(['name' => $configData->getConfigCase()]);
            $command->addItem($case[0]);
        }

        if($configData->getConfigPower()){
            $power = $this->itemRepo->findBy(['name' => $configData->getConfigPower()]);
            $command->addItem($power[0]);
        }

        if($deviceData->getDeviceScreen()){
            $screen = $this->itemRepo->findBy(['name' => $deviceData->getDeviceScreen()]);
            $command->addItem($screen[0]);
        }

        if($deviceData->getDeviceKeyboard()){
            $keyboard = $this->itemRepo->findBy(['name' => $deviceData->getDeviceKeyboard()]);
            $command->addItem($keyboard[0]);
        }

        if($deviceData->getDeviceMouse()){
            $mouse = $this->itemRepo->findBy(['name' => $deviceData->getDeviceMouse()]);
            $command->addItem($mouse[0]);
        }

        if($deviceData->getDeviceMousepad()){
            $mousepad = $this->itemRepo->findBy(['name' => $deviceData->getDeviceMousepad()]);
            $command->addItem($mousepad[0]);
        }

        if($deviceData->getDeviceHeadphone()){
            $headphone = $this->itemRepo->findBy(['name' => $deviceData->getDeviceHeadphone()]);
            $command->addItem($headphone[0]);
        }

        if($deviceData->getDeviceEnceinte()){
            $enceinte = $this->itemRepo->findBy(['name' => $deviceData->getDeviceEnceinte()]);
            $command->addItem($enceinte[0]);
        }

        if($deviceData->getDeviceWebcam()){
            $webcam = $this->itemRepo->findBy(['name' => $deviceData->getDeviceWebcam()]);
            $command->addItem($webcam[0]);
        }

        if($deviceData->getDevicePrinter()){
            $printer = $this->itemRepo->findBy(['name' => $deviceData->getDevicePrinter()]);
            $command->addItem($printer[0]);
        }

        $this->em->persist($command);
        $this->em->flush();
    }

    public function sendFirstMessage(Command $command, LifecycleEventArgs $event)
    {
        $user = $command->getUser();
        $data = $command->getCommandData();
        $configData = $data->getCommandConfigData();
        $specData = $data->getCommandSpecData();
        $deviceData = $data->getCommandDeviceData();

        $messageContent = 'Salut ! Je suis ' . $user->getUsername() . ". " . "\n";

        if($data->getBudget()){
            $messageContent .= "J'ai un budget de " . $data->getAmount() . " et une marge de " . $data->getGap() . ". ";
        }

        $messageContent .= " J'utiliserais ce pc pour du " .$data->getUtilisation() . ". ";

        if(!empty($data->getUtilisationDetails())){
            $messageContent .= "explication : " .$data->getUtilisationDetails();
        }

        if($configData->getPreconfiguration()){
            $messageContent .= "J'ai déjà des idées de pièces tel que : ";

            if(!empty($configData->getConfigProcModel())){
                $messageContent .= "ce processeur : " . $configData->getConfigProcModel();
                if(!empty($configData->getConfigProcLink())){
                    $messageContent .= " le lien : " . $configData->getConfigProcLink();
                }
            }
            else {
                if(!empty($configData->getConfigProc())){
                    $proc = $this->itemRepo->findBy(['name' => $configData->getConfigProc()]);
                    $messageContent .= "ce prossesseur : " . $proc[0]->getName() . ", le lien : " . $proc[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigBoardModel())){
                $messageContent .= "cette carte mère : " . $configData->getConfigBoardModel();
                if(!empty($configData->getConfigBoardLink())){
                    $messageContent .= " le lien : " . $configData->getConfigBoardLink();
                }
            }
            else {
                if(!empty($configData->getConfigBoard())){
                    $board = $this->itemRepo->findBy(['name' => $configData->getConfigBoard()]);
                    $messageContent .= "cette carte mère  : " . $board[0]->getName() . ", le lien : " . $board[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigGcModel())){
                $messageContent .= "cette carte graphique : " . $configData->getConfigGcModel();
                if(!empty($configData->getConfigGcLink())){
                    $messageContent .= " le lien : " . $configData->getConfigGcLink();
                }
            }
            else {
                if(!empty($configData->getConfigGc())){
                    $gc = $this->itemRepo->findBy(['name' => $configData->getConfigGc()]);
                    $messageContent .= "cette carte graphique : " . $gc[0]->getName() . ", le lien : " . $gc[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigRamModel())){
                $messageContent .= "cette ram : " . $configData->getConfigRamModel();
                if(!empty($configData->getConfigRamLink())){
                    $messageContent .= " le lien : " . $configData->getConfigRamLink();
                }
            }
            else {
                if(!empty($configData->getConfigRam())){
                    $ram = $this->itemRepo->findBy(['name' => $configData->getConfigRam()]);
                    $messageContent .= "cette ram  : " . $ram[0]->getName() . ", le lien : " . $ram[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigRefreshModel())){
                $messageContent .= "ce ventilateur : " . $configData->getConfigRefreshModel();
                if(!empty($configData->getConfigRefreshLink())){
                    $messageContent .= " le lien : " . $configData->getConfigRefreshLink();
                }
            }
            else {
                if(!empty($configData->getConfigRefresh())){
                    $refresh = $this->itemRepo->findBy(['name' => $configData->getConfigRefresh()]);
                    $messageContent .= "ce ventilateur : " . $refresh[0]->getName() . ", le lien : " . $refresh[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigStorageModel())){
                $messageContent .= "ce stockage : " . $configData->getConfigStorageModel();
                if(!empty($configData->getConfigStorageLink())){
                    $messageContent .= " le lien : " . $configData->getConfigStorageLink();
                }
            }
            else {
                if(!empty($configData->getConfigStorage())){
                    $storage = $this->itemRepo->findBy(['name' => $configData->getConfigStorage()]);
                    $messageContent .= "ce stockage : " . $storage[0]->getName() . ", le lien : " . $storage[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigBoardsoundModel())){
                $messageContent .= "cette carte son: " . $configData->getConfigBoardsoundModel();
                if(!empty($configData->getConfigBoardsoundLink())){
                    $messageContent .= " le lien : " . $configData->getConfigBoardsoundLink();
                }
            }
            else {
                if(!empty($configData->getConfigBoardsound())){
                    $boardsound = $this->itemRepo->findBy(['name' => $configData->getConfigBoardsound()]);
                    $messageContent .= "cette carte son: " . $boardsound[0]->getName() . ", le lien : " . $boardsound[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigCaseModel())){
                $messageContent .= "ce boîtier : " . $configData->getConfigCaseModel();
                if(!empty($configData->getConfigCaseLink())){
                    $messageContent .= " le lien : " . $configData->getConfigCaseLink();
                }
            }
            else {
                if(!empty($configData->getConfigCase())){
                    $case = $this->itemRepo->findBy(['name' => $configData->getConfigCase()]);
                    $messageContent .= "ce boîtier : " . $case[0]->getName() . ", le lien : " . $case[0]->getUrl();
                }
            }

            if(!empty($configData->getConfigPowerModel())){
                $messageContent .= "cette alimentation : " . $configData->getConfigPowerModel();
                if(!empty($configData->getConfigPowerLink())){
                    $messageContent .= " le lien : " . $configData->getConfigPowerLink();
                }
            }
            else {
                if(!empty($configData->getConfigPower())){
                    $power = $this->itemRepo->findBy(['name' => $configData->getConfigPower()]);
                    $messageContent .= "cette alimentation : " . $power[0]->getName() . ", le lien : " . $power[0]->getUrl();
                }
            }  
        }
        else {
            $messageContent .= "Je n'ai pas d'idée prédéfinie concernant les composants du pc. ";
        }

        if(!empty($specData->getSpecImportant())){
            $messageContent .= "Le plus important pour moi est : " . $specData->getSpecImportant() . ", ";
        }

        if($specData->getSpecSli()){
            $messageContent .= "je suis intéressé par le SLI, ";
        }

        if($specData->getSpecOverclock()){
            $messageContent .= "je suis intéressé par l'overclocking. ";
        }

        if(!empty($specData->getSpecStorage())){
            $messageContent .= "Je voudrais comme stockage du " . $specData->getSpecStorage();
            if(!empty($specData->getSpecStorageQuantity())){
                $messageContent .= ", d'au moins " . $specData->getSpecStorageQuantity() . "GO. ";
            }
            else {
                $messageContent .= ". ";
            }
        }

        if($specData->getSpecWifi()){
            $messageContent .= "Je suis intéréssé par une carte wifi, ";

            if($specData->getSpecWifiRoom()){
                $messageContent .= "et je me trouve dans la même pièce que le routeur, ";
            }
            else {
                $messageContent .= "et je ne me trouve pas dans la même pièce que le routeur, ";
            }

            if($specData->getSpecFiber()){
                $messageContent .= "et je possède la fibre. ";
            }
            else {
                $messageContent .= "et je ne possède pas la fibre. ";
            }
        }

        if($specData->getSpecSound()){
            if(!empty($specData->getSpecSoundUtilisation())){
                $messageContent .= "Je suis intéréssé par une carte son pour du " . $specData->getSpecSoundUtilisation() . ". ";
            }
            if(!empty($specData->getSpecSoundUtilisationOther())){
                $messageContent .= "Je suis intéréssé par une carte son pour : " . $specData->getSpecSoundUtilisationOther();
            }
        }

        if(!empty($specData->getSpecLight())){
            $messageContent .= "J'aimerais tel spécificités en plus : " . $specData->getSpecLight();
        }
        
        if($specData->getOs()){
            if(!empty($specData->getOschoice())){
                $messageContent .= "Je voudrais " . $specData->getOschoice() . " comme systeme d'exploitation"; 

                if($specData->getOsActive()){
                    $messageContent .= ", et je souhaiterais que vous me l'activez. ";
                }
                else {
                    $messageContent .= ", et je l'activerais moi même. ";
                }
            }

            if(!empty($specData->getOsName())){
                $messageContent .= "Serait t-il possible d'avoir " . $specData->getOsName() . " comme système d'exploitation ? ";
            }

        }
        else {
            $messageContent .= "Je n'ai pas besoin de systeme d'exploitation";
        }

        if($deviceData->getDevice()){

            $messageContent .= "Je voudrais aussi ces périphériques : ";

            if(!empty($deviceData->getDeviceScreenModel())){
                $messageContent .= "cet écran : " . $deviceData->getDeviceScreenModel();
                if(!empty($deviceData->getDeviceScreenLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceScreenLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceScreen())){
                    $screen = $this->itemRepo->findBy(['name' => $deviceData->getDeviceScreen()]);
                    $messageContent .= "cet écran : " . $screen[0]->getName() . " le lien : " . $screen[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceScreenSize())){
                $messageContent .= "Je voudrais que l'écran fasse " . $deviceData->getDeviceScreenSize(); 
            }
            if(!empty($deviceData->getDeviceScreenRes())){
                $messageContent .= "Je voudrais que l'écran ait une résolution de " . $deviceData->getDeviceScreenRes();
            }

            if(!empty($deviceData->getDeviceKeyboardModel())){
                $messageContent .= "Ce clavier : " . $deviceData->getDeviceKeyboardModel();
                if(!empty($deviceData->getDeviceKeyboardLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceKeyboardLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceKeyboard())){
                    $keyboard = $this->itemRepo->findBy(['name' => $deviceData->getDeviceKeyboard()]);
                    $messageContent .= "Ce clavier : " . $keyboard[0]->getName() . " le lien : " . $keyboard[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceKeyboardType())){
                $messageContent .= "Je voudrais que le clavier soit du type " . $deviceData->getDeviceKeyboardType();
            }
            if(!empty($deviceData->getDeviceKeyboardSwitch())){
                $messageContent .= "Je voudrais que le clavier ait des switchs  " . $deviceData->getDeviceKeyboardSwitch();
            }
            if(!empty($deviceData->getDeviceKeyboardLanguage())){
                $messageContent .= "Je voudrais que le clavier soit en " . $deviceData->getDeviceKeyboardLanguage();
            }

            if(!empty($deviceData->getDeviceMouseModel())){
                $messageContent .= "Cette souris : " . $deviceData->getDeviceMouseModel();
                if(!empty($deviceData->getDeviceMouseLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceMouseLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceMouse())){
                    $mouse = $this->itemRepo->findBy(['name' => $deviceData->getDeviceMouse()]);
                    $messageContent .= "Cette souris : " . $mouse[0]->getName() . " le lien : " . $mouse[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceMouseType())){
                $messageContent .= "Je voudrais que la souris soit du type  " . $deviceData->getDeviceMouseType();
            }
            if($deviceData->getDeviceMouseFilaire()){
                $messageContent .= "Je voudrais une souris filaire";                
            }
            else {
                $messageContent .= "Je voudrais une souris sans fil";
            }

            if(!empty($deviceData->getDeviceMouseModel())){
                $messageContent .= "Ce tapis de souris : " . $deviceData->getDeviceMouseModel();
                if(!empty($deviceData->getDeviceMouseLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceMouseLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceMousepad())){
                    $mousepad = $this->itemRepo->findBy(['name' => $deviceData->getDeviceMousepad()]);
                    $messageContent .= "Ce tapis de souris : " . $mousepad[0]->getName() . " le lien : " . $mousepad[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceMousepadType())){
                $messageContent .= "Je voudrais que le tapis de souris soit " . $deviceData->getDeviceMousepadType();
            }
            if(!empty($deviceData->getDeviceMousepadSize())){
                $messageContent .= "Je voudrais que le tapis de souris ait une taille " . $deviceData->getDeviceMousepadSize();
            }

            if(!empty($deviceData->getDeviceHeadphoneModel())){
                $messageContent .= "Ce casque : " . $deviceData->getDeviceHeadphoneModel();
                if(!empty($deviceData->getDeviceHeadphoneLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceHeadphoneLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceHeadphone())){
                    $headphone = $this->itemRepo->findBy(['name' => $deviceData->getDeviceHeadphone()]);
                    $messageContent .= "Ce casque : " . $headphone[0]->getName() . " le lien : " . $headphone[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceHeadphoneType())){
                $messageContent .= "Je voudrais que le casque soit de type " . $deviceData->getDeviceHeadphoneType();
            }
            if(!empty($deviceData->getDeviceHeadphoneSize())){
                $messageContent .= "Je voudrais que le casque soit de taille " . $deviceData->getDeviceHeadphoneSize();
            }

            if(!empty($deviceData->getDeviceEnceinteModel())){
                $messageContent .= "Cette enceinte : " . $deviceData->getDeviceEnceinteModel();
                if(!empty($deviceData->getDeviceEnceinteLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceEnceinteLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceEnceinte())){
                    $enceinte = $this->itemRepo->findBy(['name' => $deviceData->getDeviceEnceinte()]);
                    $messageContent .= "Cette enceinte : " . $enceinte[0]->getName() . " le lien : " . $enceinte[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceEnceinteType())){
                $messageContent .= "Je voudrais  " . $deviceData->getDeviceEnceinteType();
            }
            if($deviceData->getDeviceEnceinteBass()){
                $messageContent .= "Je voudrais un caisson de basse";      
            }
            if(!empty($deviceData->getDeviceWebcamModel())){
                $messageContent .= "Cette webcam : " . $deviceData->getDeviceWebcamModel();
                if(!empty($deviceData->getDeviceWebcamLink())){
                    $messageContent .= " le lien : " . $deviceData->getDeviceWebcamLink();
                }
            } 
            else {
                if(!empty($deviceData->getDeviceWebcam())){
                    $webcam = $this->itemRepo->findBy(['name' => $deviceData->getDeviceWebcam()]);
                    $messageContent .= "Cette webcam : " . $webcam[0]->getName() . " le lien : " . $webcam[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDeviceWebcamRes())){
                $messageContent .= "Je voudrais que la webcam ait une résolution de " . $deviceData->getDeviceWebcamRes();
            }

            if(!empty($deviceData->getDevicePrinterModel())){
                $messageContent .= "Cette imprimante : " . $deviceData->getDevicePrinterModel();
                if(!empty($deviceData->getDevicePrinterLink())){
                    $messageContent .= " le lien : " . $deviceData->getDevicePrinterLink();
                }
            } 
            else {
                if(!empty($deviceData->getDevicePrinter())){
                    $printer = $this->itemRepo->findBy(['name' => $deviceData->getDevicePrinter()]);
                    $messageContent .= "Cette imprimante : " . $printer[0]->getName() . " le lien : " . $printer[0]->getUrl();
                }
            }
            if(!empty($deviceData->getDevicePrinterType())){
                $messageContent .= "Je voudrais  que l'imprimante soit de type " . $deviceData->getDevicePrinterType();
            }
        }

        $message = new Message;

        $message->setContent($messageContent);
        $message->setFromUser($user);
        $message->setToUser($user->getBuilder());

        $this->em->persist($message);
        $this->em->flush();
    }
}