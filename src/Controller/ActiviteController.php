<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Document;
use App\Form\AddActiviteFormType;
use App\Form\DocumentFormType;
use App\Form\UpdateActiviteFormType;
use App\Repository\ActiviteRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ActiviteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ActiviteRepository $activiteRepository, private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/activite', name: 'app_activite')]
    public function index(): Response
    {
        $activites = $this->activiteRepository->findAll();
        return $this->render('activite/index.html.twig', [
            'controller_name' => 'ActiviteController',
            'activites' => $activites,
        ]);
    }

    #[Route('apis/activites', name:'APICreateActivite', methods:['POST'])]
    public function APICreateActivite(Request $request)
    {
        $activite = $this->serializerInterface->deserialize($request->getContent(), Activite::class, 'json', ['groups' => 'getActivite']);

        $this->entityManager->persist($activite);
        $this->entityManager->flush();

        $activite = $this->serializerInterface->serialize($activite, 'json', ['groups' => 'getActivite']);

        return new JsonResponse($activite, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/activites', name:'APIGetAllActivites', methods:['GET'])]
    public function APIGetAllActivites()
    {
        $activites = $this->activiteRepository->findAll();

        $activites = $this->serializerInterface->serialize($activites, 'json', ['groups' => 'getActivite']);

        return new JsonResponse($activites, Response::HTTP_OK, [], true);
    }

    #[Route('apis/activites/{id}', name:'APIGetActivites', methods:['GET'])]
    public function APIGetActivites($id)
    {
        $activite = $this->activiteRepository->find($id);

        $activite = $this->serializerInterface->serialize($activite, 'json', ['groups' => 'getActivite']);

        return new JsonResponse($activite, Response::HTTP_OK, [], true);
    }

    #[Route('apis/activites/{id}', name:'APIUpdateActivite', methods:['PUT'])]
    public function APIUpdateActivite(Request $request, $id)
    {
        $activite = $this->activiteRepository->find($id);
        $documents = $activite->getDocuments();

        foreach ($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $update = $this->serializerInterface->deserialize($request->getContent(), Activite::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $activite, 'groups' => 'getActivite']);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $activite = $this->serializerInterface->serialize($update, 'json', ['groups' => 'getActivite']);

        return new JsonResponse($activite, Response::HTTP_OK, [], true);
    }

    #[Route('apis/activites/{id}', name:'APIDeleteActivite', methods:['DELETE'])]
    public function APIDeleteActivite($id)
    {
        $activite = $this->activiteRepository->find($id);
        $documents = $activite->getDocuments();

        foreach ($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $this->entityManager->remove($activite);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/addactivite' , name: 'AddActivite')]
    // public function AddActivite(Request $request)
    // {
    //     $activite = new Activite();

    //     $form = $this->createForm(AddActiviteFormType::class , $activite);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $activite->setIntitule($form->get('intitule')->getData());
    //         $activite->setStatut(false);
    //         $activite->setDebut($form->get('debut')->getData());
    //         $activite->setFin($form->get('fin')->getData());

    //         $this->entityManager->persist($activite);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_activite');
    //     }

    //     return $this->render('activite/add.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/detailsactivite/{id}' , name: 'DetailsActivite')]
    // public function DetailsActivite($id)
    // {
    //     $activite = $this->activiteRepository->find($id);
    //     $documents = $activite->getDocuments();

    //     return $this->render('activite/details.html.twig' , [
    //         'activite' => $activite,
    //         'documents' => $documents,
    //     ]);
    // }

    // #[Route('updateactivite/{id}' , name: 'UpdateActivite')]
    // public function UpdateActivite($id , Request $request)
    // {
    //     $activite = $this->activiteRepository->find($id);

    //     $form = $this->createForm(UpdateActiviteFormType::class, $activite);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $activite->setFinReel($form->get('finReel')->getData());
    //         $activite->setStatut(true);

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_activite');
    //     }

    //     return $this->render('activite/update.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('deleteactivite/{id}' , name: 'DeleteActivite')]
    // public function DeleteActivite($id)
    // {
    //     $activite = $this->activiteRepository->find($id);

    //     $this->entityManager->remove($activite);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_activite');
    // }

    // #[Route('/addactivitedocument/{id}', name:'AddActiviteDocument')]
    // public function AddDocument(Request $request, $id)
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
            
    //         $activite = $this->activiteRepository->find($id);

    //         $activite->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirect('/detailsactivite/'. $activite->getId());
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/detailsactivite/{ida}/deletedocument/{idd}', name:'DeleteActiviteDocument')]
    // public function DeleteActiviteDocument($ida, $idd)
    // {
    //     $document = $this->documentRepository->find($idd);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirect('/detailsactivite/'. $ida);
    // }
}
