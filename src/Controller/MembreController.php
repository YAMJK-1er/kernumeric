<?php

namespace App\Controller;

use App\Entity\CreateMembre;
use App\Entity\Document;
use App\Entity\Membre;
use App\Entity\Personne;
use App\Form\CreateMembreFormType;
use App\Form\DocumentFormType;
use App\Repository\DocumentRepository;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MembreController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MembreRepository $membreRepository, private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/membre', name: 'app_membre')]
    public function index(): Response
    {
        $membres = $this->membreRepository->findAll();

        return $this->render('membre/index.html.twig', [
            'controller_name' => 'MembreController',
            'membres' => $membres,
        ]);
    }

    #[Route('apis/membres', name:'APICreateMembre', methods:['POST'])]
    public function APICreateMembre(Request $request)
    {
        $membre = $this->serializerInterface->deserialize($request->getContent(), Membre::class, 'json');

        $this->entityManager->persist($membre);
        $this->entityManager->flush();

        $membre = $this->serializerInterface->serialize($membre, 'json', ['groups' => 'getMembre']);

        return new JsonResponse($membre, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/membres', name:'APIGetAllMembres', methods:['GET'])]
    public function APIGetAllMembres()
    {
        $membres = $this->membreRepository->findAll();

        $membres = $this->serializerInterface->serialize($membres, 'json', ['groups' => 'getMembre']);
        
        return new JsonResponse($membres, Response::HTTP_OK, [], true);
    }

    #[Route('apis/membres/{id}', name:'APIGetMembre', methods:['GET'])]
    public function APIGetMembres($id)
    {
        $membre = $this->membreRepository->find($id);

        $membre = $this->serializerInterface->serialize($membre, 'json', ['groups' => 'getMembre']);

        return new JsonResponse($membre, Response::HTTP_OK, [], true);
    }

    #[Route('apis/membres/{id}', name:'APIUpdateMembre', methods:['PUT'])]
    public function APIUpdateMembre(Request $request, $id)
    {
        $membre = $this->membreRepository->find($id);
        $documents = $membre->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $update = $this->serializerInterface->deserialize($request->getContent(), Membre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $membre, 'groups' => 'getMembre']);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $membre = $this->serializerInterface->serialize($update, 'json', ['groups' => 'getMembre']);

        return new JsonResponse($membre, Response::HTTP_OK, [], true);
    }

    #[Route('apis/membres/{id}', name:'APIDeleteMembre', methods:['DELETE'])]
    public function APIDeleteMembre($id)
    {
        $membre = $this->membreRepository->find($id);
        $documents = $membre->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $this->entityManager->remove($membre);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/addmembre' , name:'AddMembre')]
    // public function AddAdherent(Request $request)
    // {
    //     $create = new CreateMembre();

    //     $form = $this->createForm(CreateMembreFormType::class , $create);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $personne = new Personne();
    //         $personne->setNom($form->get('nom')->getData());
    //         $personne->setPrenom($form->get('prenom')->getData());
    //         $personne->setEmail($form->get('email')->getData());
    //         $personne->setTelephone($form->get('telephone')->getData());
    //         $personne->setPays($form->get('pays')->getData());
    //         $personne->setVille($form->get('ville')->getData());
    //         $personne->setCommentaire($form->get('commentaire')->getData());

    //         $membre = new Membre();

    //         $membre->setPersonne($personne);
    //         $membre->setPoste($form->get('poste')->getData());

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($membre);
    //         $this->entityManager->flush();
            
    //         return $this->redirectToRoute('app_membre');
    //     }

    //     return $this->render('membre/addmembre.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatemembre/{id}' , name:'UpdateMembre')]
    // public function UpdateAdherent(Request $request, $id)
    // {
    //     $membre = $this->membreRepository->find($id);

    //     $create = new CreateMembre();

    //     $create->setNom($membre->getPersonne()->getNom());
    //     $create->setPrenom($membre->getPersonne()->getPrenom());
    //     $create->setEmail($membre->getPersonne()->getEmail());
    //     $create->setTelephone($membre->getPersonne()->getTelephone());
    //     $create->setPays($membre->getPersonne()->getPays());
    //     $create->setVille($membre->getPersonne()->getVille());
    //     $create->setCommentaire($membre->getPersonne()->getCommentaire());
    //     $create->setPoste($membre->getPoste());

    //     $form = $this->createForm(CreateMembreFormType::class , $create);

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $personne = new Personne();
    //         $personne->setNom($form->get('nom')->getData());
    //         $personne->setPrenom($form->get('prenom')->getData());
    //         $personne->setEmail($form->get('email')->getData());
    //         $personne->setTelephone($form->get('telephone')->getData());
    //         $personne->setPays($form->get('pays')->getData());
    //         $personne->setVille($form->get('ville')->getData());
    //         $personne->setCommentaire($form->get('commentaire')->getData());

    //         $membre->setPersonne($personne);
    //         $membre->setPoste($form->get('poste')->getData());

    //         $this->entityManager->flush();
            
    //         return $this->redirectToRoute('app_membre');
    //     }

    //     return $this->render('membre/updatemembre.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deleteMembre/{id}' , name:'DeleteMembre')]
    // public function DeleteMembre($id)
    // {
    //     $membre = $this->membreRepository->find($id);

    //     $this->entityManager->remove($membre);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_membre');
    // }

    // #[Route('/membre/{idm}/adddocument', name:'AddMembreDocument')]
    // public function AddMembreDocument(Request $request, $idm)
    // {
    //     $document = new Document();

    //     $form = $this->createForm(DocumentFormType::class, $document);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $document->setType($form->get('type')->getData());

    //         $url = $form->get('url')->getData();

    //         if ($url)
    //         {
    //             $file = uniqid() . '.' . $url->guessExtension();

    //             try
    //             {
    //                 $url->move($this->getParameter('kernel.project_dir') . '/public/documents' , $file);
    //             }
    //             catch (FileException $e)
    //             {
    //                 return new Response($e->getMessage());
    //             }

    //             $document->setUrl('/documents/' . $file);
    //         }

    //         $membre = $this->membreRepository->find($idm);

    //         $membre->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_membre');
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletemembredocument/{id}', name:'DeleteMembreDocument')]
    // public function DeleteMembreDocument($id)
    // {
    //     $document = $this->documentRepository->find($id);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_membre');
    // }
}
