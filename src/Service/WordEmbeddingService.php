<?php

namespace App\Service;

use App\Repository\WordRepository;
use http\Env\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WordEmbeddingService
{

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }


    public function calculateEmbedding($word): array
    {
        $response = $this->client->request(
            'POST',
            'http://127.0.0.1:11434/api/embeddings'
        ,[
            "headers"=>[
              'Content-Type'=>'application/json'
            ],
            "json"=>[
                "model"=>"mistral",
                "prompt"=>$word
            ]
        ]
        );

        $content = $response->toArray();


        return $content;
    }


    public function cosine_similarity($vector1, $vector2) {
        $dotProduct = array_sum(array_map(function($x, $y) {
            return $x * $y;
        }, $vector1, $vector2));

        $magnitude1 = sqrt(array_sum(array_map(function($x) {
            return $x * $x;
        }, $vector1)));

        $magnitude2 = sqrt(array_sum(array_map(function($x) {
            return $x * $x;
        }, $vector2)));

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        return round(($dotProduct / ($magnitude1 * $magnitude2))*100, 2);
    }

}