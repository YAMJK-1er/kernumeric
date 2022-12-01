<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\MembreRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PosteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private PosteRepository $posteRepository, private SerializerInterface $serializerInterface){}

    #[Route('/poste', name: 'app_poste')]
    public function index(): Response
    {
        $postes = $this->posteRepository->findAll();

        return $this->render('poste/index.html.twig', [
            'controller_name' => 'PosteController',
            'postes' => $postes,
        ]);
    }

    // #[Route('addposte' , name:'AddPoste')]
    // public function AddPoste(Request $request)
    // {
    //     $poste = new Poste();

    //     $form = $this->createForm(PosteFormType::class, $poste);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $poste->setIntitule($form->get('intitule')->getData());

    //         $this->entityManager->persist($poste);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_poste');
    //     }

    //     return $this->render('poste/addposte.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('updateposte/{id}' , name:'UpdatePoste')]
    // public function UpdatePoste(Request $request, $id)
    // {
    //     $poste = $this->posteRepository->find($id);

    //     $form = $this->createForm(PosteFormType::class, $poste);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $poste->setIntitule($form->get('intitule')->getData());

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_poste');
    //     }

    //     return $this->render('poste/updateposte.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
        
    // }

    // #[Route('/deleteposte/{id}' , name:'DeletePoste')]
    // public function DeletePoste($id)
    // {
    //     $poste = $this->posteRepository->find($id);

    //     $membres = $poste->getMembres();

    //     foreach ($membres as $membre)
    //     {
    //         $this->entityManager->remove($membre);
    //     }

    //     $this->entityManager->remove($poste);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_poste');
    // }
}
