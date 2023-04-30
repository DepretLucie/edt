<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\SalleRepository;
use App\Entity\Salle;

#[Route('/api/salle', name: 'api_salle_')]
class SalleController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(SalleRepository $repository): Response
    {
        $salle = $repository->findAll();
        return $this->json($salle, Response::HTTP_OK);
    }
}
?>