<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CoursRepository;
use App\Repository\NoteRepository;
use App\Entity\Cours;
use App\Entity\Note;


#[Route('/api/cours', name: 'api_cours_')]
class CoursController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(CoursRepository $repository): Response
    {
        $cours = $repository->findAll();
        return $this->json($cours, Response::HTTP_OK);
    }

    #[Route('/date', name: 'list_du_jour', methods: ['GET'])]
    public function listDuJour(Request $request, CoursRepository $repository): Response
    {
        $data = $request->query->get('date');
        if(is_null($data))
        {
            return $this->json([
                'message' => 'Requête mal formattée',
            ], Response::HTTP_BAD_REQUEST);
        }
        $cours = $repository->findEdtOneDay($data);
        return $this->json($cours, Response::HTTP_OK);
    }

    #[Route('/{id}/note', name: 'create_note', methods: ['POST'])]
    public function createNote(Request $request, ?Cours $cours, NoteRepository $repository, ValidatorInterface $validator): JsonResponse
    {
        if(is_null($cours))
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
        $note = (new Note)->fromArray($data)->setCours($cours);
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
}
?>