<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\AvisMatiereRepository;
use App\Repository\MatiereRepository;
use App\Entity\AvisMatiere;
use App\Entity\Matiere;


#[Route('/api/matiere', name: 'api_matiere_')]
class AvisMatiereController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(MatiereRepository $repository): Response
    {
        $matieres = $repository->findAll();
        return $this->json($matieres, Response::HTTP_OK);
    }

    #[Route('/{id}/note', name: 'create_note', methods: ['POST'])]
    public function createNote(Request $request, ?Matiere $matiere, AvisMatiereRepository $repository, ValidatorInterface $validator): JsonResponse
    {
        if(is_null($matiere))
        {
            return $this->json([
                'message' => 'Cours not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        if(is_null($data))
        {
            return $this->json([
                'message' => 'Requête mal formattée',
            ], Response::HTTP_BAD_REQUEST);
        }
        $note = (new AvisMatiere)->fromArray($data)->setMatiere($matiere);
        $errors = $validator->validate($note);
        if($errors->count() > 0) 
        {
            $messages = [];
            foreach ($errors as $error) 
            {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($messages, Response::HTTP_BAD_REQUEST);
        }
        $repository->save($note, true);
        return $this->json($note, Response::HTTP_CREATED);
    }

    #[Route('/{id}/avis', name: 'list_avis', methods: ['GET'])]
    public function listAvis(?Matiere $matiere): JsonResponse
    {
        if(is_null($matiere))
        {
            return $this->json([
                'message' => 'Professeur not found',
            ], Response::HTTP_NOT_FOUND);
        }
        return $this->json($matiere->getAvisMatieres()->toArray(), Response::HTTP_OK);
    }
    
    #[Route('/{id}', name: 'edit', methods: ['PATCH'])]
    public function edit(Request $request, ?AvisMatiere $avis, AvisMatiereRepository $repository, ValidatorInterface $validator): JsonResponse
    {
        if(is_null($avis))
        {
            return $this->json([
                'message' => 'Avis not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        if(is_null($data))
        {
            return $this->json([
                'message' => 'Requête mal formattée',
            ], Response::HTTP_BAD_REQUEST);
        }
        $avis->fromArray($data);
        $errors = $validator->validate($avis);
        if($errors->count() > 0) 
        {
            $messages = [];
            foreach ($errors as $error) 
            {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($messages, Response::HTTP_BAD_REQUEST);
        }
        $repository->save($avis, true);
        return $this->json($avis, Response::HTTP_CREATED);
    }

    #[Route('/avis/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(AvisMatiereRepository $repository, ?AvisMatiere $avis): JsonResponse
    {
        if(is_null($avis))
        {
            return $this->json([
                'message' => 'Avis not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $repository->remove($avis, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
