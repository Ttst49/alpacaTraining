<?php

namespace App\Controller;

use App\Entity\Embedding;
use App\Entity\Word;
use App\Repository\WordRepository;
use App\Service\WordEmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(WordRepository $repository,WordEmbeddingService $service, EntityManagerInterface $manager): Response
    {
        $searchedWord = "banane";

        foreach ($repository->findBy(["value" => $searchedWord])[0]->getEmbeddings() as $embedding) {
            $searchedWordEmbeddings[] = $embedding->getValue();
        }

        
        $actualWord = "poire";
        if ($repository->findBy(["value"=>$actualWord])){
            foreach ($repository->findBy(["value"=>$actualWord])[0]->getEmbeddings() as $embedding) {
                $embeddings[] = $embedding->getValue();
                $result = $service->cosine_similarity($embeddings,$searchedWordEmbeddings);
            }
            $response = [
                "content"=>$result
            ];
            return $this->json($response,200);
        }
        $newWord = new Word();
        $newWord->setValue($actualWord);
        $content = $service->calculateEmbedding($actualWord);
        foreach ($content["embedding"] as $embedding){

          $newEmbedding = new Embedding();
          $newEmbedding->setValue($embedding);
          $newEmbedding->setWord($newWord);
          $manager->persist($newWord);
          $manager->persist($newEmbedding);
        }
        $result = "C'est tout bon";
        $manager->flush();
        $response = [
            "content"=>$result
        ];
        return $this->json($response,200);
    }

}
