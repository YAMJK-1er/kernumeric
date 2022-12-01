<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\Donateur;
use App\Entity\GestionDons;
use App\Entity\Personne;
use App\Form\GestionDonsFormType;
use App\Form\PersonneFormType;
use App\Repository\DonateurRepository;
use App\Repository\DonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DonateurController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private DonateurRepository $donateurRepository, private DonRepository $donRepository, private SerializerInterface $serializerInterface){}

    #[Route('/don', name: 'app_don')]
    public function index(): Response
    {
        $dons = $this->donRepository->findAll();
        return $this->render('donateur/index.html.twig', [
            'controller_name' => 'DonateurController',
            'dons' => $dons,
        ]);
    }

    #[Route('apis/donateurs', name:'APICreateDonateur', methods:['POST'])]
    public function APICreateDonateur(Request $request)
    {
        $donateur = $this->serializerInterface->deserialize($request->getContent(), Donateur::class, 'json');

        $this->entityManager->persist($donateur);
        $this->entityManager->flush();

        $donateur = $this->serializerInterface->serialize($donateur, 'json', ['groups' => 'getDonateur']);

        return new JsonResponse($donateur, Response::HTTP_CREATED, ['groups' => 'getDonateur'], true);
    }

    #[Route('apis/donateurs', name:'APIGetAllDonateurs', methods:['GET'])]
    public function APIGetAllDonateurs()
    {
        $donateurs = $this->donateurRepository->findAll();

        $donateurs = $this->serializerInterface->serialize($donateurs, 'json', ['groups' => 'getDonateur']);

        return new JsonResponse($donateurs, Response::HTTP_OK, ['groups' => 'getDonateur'], true);
    }

    #[Route('apis/donateurs/{id}', name:'APIGetDonateur', methods:['GET'])]
    public function APIGetDonateur($id)
    {
        $donateur = $this->donateurRepository->find($id);

        $donateur = $this->serializerInterface->serialize($donateur, 'json', ['groups' => 'getDonateur']);

        return new JsonResponse($donateur, Response::HTTP_OK, ['groups' => 'getDonateur'], true);
    }

    #[Route('apis/donateurs/{id}', name:'APIDeleteDonateur', methods:['DELETE'])]
    public function APIDeleteDonateur($id)
    {
        $donateur = $this->donateurRepository->find($id);
        
        $this->entityManager->remove($donateur);
        $this->entityManager->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/adddon' , name:'AddDon')]
    // public function AddDonateur(Request $request)
    // {
    //     $gestionDon = new GestionDons();

    //     $form = $this->createForm(GestionDonsFormType::class, $gestionDon);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $personne = new Personne();
    //         $donateur = new Donateur();
    //         $don = new Don();

    //         $personne->setNom($form->get('nom')->getData());
    //         $personne->setPrenom($form->get('prenom')->getData());
    //         $personne->setEmail($form->get('email')->getData());
    //         $personne->setTelephone($form->get('telephone')->getData());
    //         $personne->setPays($form->get('pays')->getData());
    //         $personne->setVille($form->get('ville')->getData());

    //         $donateur->setPersonne($personne);

    //         $don->setDate($form->get('date')->getData());
    //         $don->setTypePaiement($form->get('type')->getData());
    //         $don->setValeur($form->get('valeur')->getData());
    //         $don->setDonateur($donateur);

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($donateur);
    //         $this->entityManager->persist($don);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_don');
    //     }

    //     return $this->render('donateur/adddon.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
