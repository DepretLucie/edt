<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProfesseurRepository;
use App\Form\ProfesseurType;
use App\Entity\Professeur;

#[Route('/professeur', name: 'professeur_')]
class ProfesseurController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(ProfesseurRepository $repository): Response
    {
        $professeurs = $repository->findAll();
        return $this->render('professeur/list.html.twig', ['professeurs' => $professeurs,]);
    }

    #[Route('/create', name: 'create', methods: ['POST', 'GET'])]
    public function create(Request $request, ProfesseurRepository $repository): Response
    {
        $professeur = new Professeur;
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $professeur = $form->getData();
            $repository->save($professeur, true);
            return $this->redirectToRoute('professeur_list');
        }
        return $this->render('professeur/create.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, ProfesseurRepository $repository, int $id): Response
    {
        $professeur = $repository->findOneBy(array('id' => $id));
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $professeur = $form->getData();
            $repository->save($professeur, true);
            return $this->redirectToRoute('professeur_list');
        }
        return $this->render('professeur/create.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(ProfesseurRepository $repository, Professeur $professeur): Response
    {
        $repository->remove($professeur, true);
        return $this->redirectToRoute('professeur_list');
    }
}
