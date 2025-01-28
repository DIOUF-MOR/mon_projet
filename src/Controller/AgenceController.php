<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AgenceController extends AbstractController
{
    #[Route('/agence/all', name: 'app_agence_liste',methods:["GET","POST"])]
    public function lister(AgenceRepository $agenceRepository,Request $request): Response
    {
        
        $agences=$agenceRepository->findAll();
        $totalItems= count($agences);
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 5;

        $totalPages = (int) ceil($totalItems / $itemsPerPage);
        $currentPage = max(1, min($currentPage, $totalPages)); // S'assurer que la page est dans les limites
        
        $offset = ($currentPage - 1) * $itemsPerPage;
        $agences = $agenceRepository->findBy(
            [],
            ['id' => 'DESC'], 
            $itemsPerPage,
            $offset
        );

        return $this->render('agence/all.html.twig', [
            'agences'=>$agences,
            'offset' => $offset,
            'limit' => $itemsPerPage,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'hasPreviousPage' => $currentPage > 1,
            'hasNextPage' => $currentPage < $totalPages,
        ]);
    }

    #[Route('/agence/add', name: 'app_agence_add',methods:["GET","POST"])]
    public function ajouter(EntityManagerInterface $mananger,Request $request): Response
    {
        $agence=new Agence();
        $formAgence=$this->createForm(AgenceType::class,$agence);
        $formAgence->handleRequest($request);
        if ($formAgence->isSubmitted()) {
            $mananger->persist($agence);
            $mananger->flush();
            return $this->redirectToRoute('app_agence_liste');
        }
        return $this->render('agence/add.html.twig', [
            'formAgence' => $formAgence->createView(),
        ]);
    }
}
