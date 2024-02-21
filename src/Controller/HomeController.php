<?php

namespace App\Controller;

use App\Entity\Embedding;
use App\Entity\Word;
use App\Repository\DayRepository;
use App\Repository\WordRepository;
use App\Service\DayService;
use App\Service\WordEmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function checkWords(Request $request,
                               WordRepository $repository,
                               WordEmbeddingService $service,
                               EntityManagerInterface $manager,
                               DayService $dayService,
    ): Response{



        $searchedWord = $dayService->getActualDay()->getWord();


        $actualWord = $request->getPayload()->get("word");

        $searchedWordArray =$repository->findBy(["value" => $searchedWord])[0];
        $actualWordArray = $repository->findBy(["value"=>$actualWord]);

        foreach ($searchedWordArray->getEmbeddings() as $embedding) {
            $searchedWordEmbeddings[] = $embedding->getValue();
        }


        if ($actualWordArray){
            foreach ($actualWordArray[0]->getEmbeddings() as $embedding) {
                $embeddings[] = $embedding->getValue();
            }
        }else{
            $newWord = new Word();
            $newWord->setValue($actualWord);
            $content = $service->calculateEmbedding($actualWord);
            foreach ($content["embedding"] as $embedding){
                $embeddings[] = $embedding;
                $newEmbedding = new Embedding();
                $newEmbedding->setValue($embedding);
                $newEmbedding->setWord($newWord);
                $manager->persist($newWord);
                $manager->persist($newEmbedding);
            }
            $manager->flush();
        }


        $result = $service->cosine_similarity($embeddings,$searchedWordEmbeddings);
        $response = [
            "value"=>$result
        ];
        return $this->json($response,200);
    }


    public function getDayInformation(DayRepository $repository){
        return $repository->findAll();
    }

}
