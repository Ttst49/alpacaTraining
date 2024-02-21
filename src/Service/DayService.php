<?php

namespace App\Service;


use App\Entity\Day;
use App\Repository\DayRepository;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;

class DayService{

    private ?Day $actualDay;

    public function __construct(WordRepository $repository,
                                EntityManagerInterface $manager,
                                DayRepository $dayRepository,
    ){
        $this->actualDay = $dayRepository->findOneBy([],["id"=>"desc"]);

        if (!$this->actualDay || $this->actualDay->getDay() != new \DateTime("today")){
            $this->actualDay = new Day();
            $this->actualDay->setDay(new \DateTime("today"));
            $this->actualDay->setWord($repository->getRandomWord());
            $manager->persist($this->actualDay);
            $manager->flush();
        }
    }

    public function getActualDay(): Day
    {
        return $this->actualDay;
    }


}