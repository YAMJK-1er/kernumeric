<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Personne;
use App\Form\PersonneFormType;
use App\Repository\AdherentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class AdherentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private AdherentRepository $adherentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/adherent', name: 'app_adherent')]
    public function index(): Response
    {
        $adherents = $this->adherentRepository->findAll();

        return $this->render('adherent/index.html.twig', [
            'controller_name' => 'AdherentController',
            'adherents' => $adherents,
        ]);
    }

    #[Route('apis/adherents', name:'APIGetAllAdherents', methods:['GET'])]
    public function GetAllAdherents() : JsonResponse
    {
        $adherents = $this->adherentRepository->findAll();

        $adherents = $this->serializerInterface->serialize($adherents, 'json');

        return new JsonResponse($adherents, Response::HTTP_OK, [], true);
    }

    #[Route('apis/adherents/{id}', name:'APIGEtAdherent', methods:['GET'])]
    public function GetAdherent($id) : JsonResponse
    {
        $adherent = $this->adherentRepository->find($id);

        if($adherent)
        {
            $adherent = $this->serializerInterface->serialize($adherent, 'json');

            return new JsonResponse($adherent, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('apis/adherents/{id}', name:'APIDeleteAdherent', methods:['DELETE'])]
    public function APIDeleteAdherent($id)
    {
        $adherent = $this->adherentRepository->find($id);

        $this->entityManager->remove($adherent);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('apis/adherents', name:'ApiCreateAdherent', methods:['POST'])]
    public function APICreateAdherent(Request $request)
    {
        $adherent = $this->serializerInterface->deserialize($request->getContent(), Adherent::class, 'json');

        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        $adherent = $this->serializerInterface->serialize($adherent, 'json');

        return new JsonResponse($adherent, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/adherents/{id}', name:'APIUpdateAdherent', methods:['PUT'])]
    public function APIUpdateAdherent(Request $request, $id)
    {
        $adherent = $this->adherentRepository->find($id);

        $update = $this->serializerInterface->deserialize($request->getContent(), Adherent::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $adherent]);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $adherent = $this->serializerInterface->serialize($update, 'json');

        return new JsonResponse($adherent, Response::HTTP_OK, [], true);
    }

    // #[Route('/addadherent' , name:'AddAdherent')]
    // public function AddAdherent(Request $request)
    // {
    //     $personne = new Personne();

    //     $form = $this->createForm(PersonneFormType::class , $personne);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $personne->setNom($form->get('nom')->getData());
    //         $personne->setPrenom($form->get('prenom')->getData());
    //         $personne->setEmail($form->get('email')->getData());
    //         $personne->setTelephone($form->get('telephone')->getData());
    //         $personne->setPays($form->get('pays')->getData());
    //         $personne->setVille($form->get('ville')->getData());
    //         if($form->get('commentaire')->getData())
    //         {
    //             $personne->setCommentaire($form->get('commentaire')->getData());
    //         }

    //         $adherent = new Adherent();

    //         $adherent->setPersonne($personne);

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($adherent);
    //         $this->entityManager->flush();
    //         return $this->redirectToRoute('app_adherent');
    //     }

    //     return $this->render('adherent/addadherent.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updateadherent/{id}' , name:'UpdateAdherent')]
    // public function UpdateAdherent(Request $request, $id)
    // {
    //     $adherent = $this->adherentRepository->find($id);
    //     $personne = $adherent->getPersonne();

    //     $form = $this->createForm(PersonneFormType::class , $personne);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $personne->setNom($form->get('nom')->getData());
    //         $personne->setPrenom($form->get('prenom')->getData());
    //         $personne->setEmail($form->get('email')->getData());
    //         $personne->setTelephone($form->get('telephone')->getData());
    //         $personne->setPays($form->get('pays')->getData());
    //         $personne->setVille($form->get('ville')->getData());
    //         $personne->setCommentaire($form->get('commentaire')->getData());

    //         $adherent->setPersonne($personne);

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_adherent');
    //     }

    //     return $this->render('adherent/updateadherent.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deleteadherent/{id}' , name:'DeleteAdherent')]
    // public function DeleteAdherent($id)
    // {
    //     $adherent = $this->adherentRepository->find($id);

    //     $this->entityManager->remove($adherent);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_adherent');
    // }
}
