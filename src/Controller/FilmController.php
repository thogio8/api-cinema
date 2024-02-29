<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class FilmController extends AbstractController
{
    #[Route('/films', name: 'api_films', methods: ['GET'])]
    public function index(FilmRepository $repository, SerializerInterface $serializer): Response
    {
        $films = $repository->findAll();
        $filmsSerialized = $serializer->serialize($films, 'json', ['groups' => 'list_films']);
        return new Response($filmsSerialized, 200, [
            'content-type' => 'application/json'
        ]);
    }
}
