<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Document;
use App\Form\AssociationFormType;
use App\Form\DocumentFormType;
use App\Repository\AssociationRepository;
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

class AssociationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private AssociationRepository $associationRepository , private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/association', name: 'app_association')]
    public function index(): Response
    {
        $association = $this->associationRepository->find(1);
        $documents = $association->getDocuments();

        return $this->render('association/index.html.twig', [
            'controller_name' => 'AssociationController',
            'assoc' => $association,
            'documents' => $documents,
        ]);
    }

    #[Route('apis/associations', name:'APICreateAssociation', methods:['POST'])]
    public function APICreateAssociation(Request $request)
    {
        $association = $this->serializerInterface->deserialize($request->getContent(), Association::class, 'json', ['groups' => 'getAssociation']);

        $this->entityManager->persist($association);
        $this->entityManager->flush();

        $association = $this->serializerInterface->serialize($association, 'json', ['groups' => 'getAssociation']);

        return new JsonResponse($association, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/associations', methods:['GET'], name:'APIGetAllAssociation')]
    public function APIGetAllAssociation()
    {
        $associations = $this->associationRepository->findAll();

        $associations = $this->serializerInterface->serialize($associations, 'json', ['groups' => 'getAssociation']);

        return new JsonResponse($associations, Response::HTTP_OK, [], true);
    }

    #[Route('apis/associations/{id}', name:'APIGetAssociation', methods:['GET'])]
    public function APIGetAssociation($id)
    {
        $association = $this->associationRepository->find($id);

        $association = $this->serializerInterface->serialize($association, 'json', ['groups' => 'getAssociation']);

        return new JsonResponse($association, Response::HTTP_OK, [], true);
    }

    #[Route('apis/associations/{id}', name:'APIUpdateAssociation', methods:['PUT'])]
    public function APIUpdateAssociation(Request $request, $id)
    {
        $association = $this->associationRepository->find($id);
        $documents = $association->getDocuments();

        foreach ($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $update = $this->serializerInterface->deserialize($request->getContent(), Association::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $association, 'groups' => 'getAssociation']);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $association = $this->serializerInterface->serialize($update, 'json', ['groups' => 'getAssociation']);

        return new JsonResponse($association, Response::HTTP_OK, [], true);
    }

    #[Route('apis/associations/{id}', name:'APIDeleteAssociation', methods:['DELETE'])]
    public function APIDeleteAssociation($id)
    {
        $association = $this->associationRepository->find($id);
        $documents = $association->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $this->entityManager->remove($association);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/createassociation' , name: 'CreateAssociation')]
    // public function CreateAssociation(Request $request)
    // {
    //     $association = new Association();

    //     $form = $this->createForm(AssociationFormType::class, $association);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $association->setNom($form->get('nom')->getData());
    //         $association->setNumero($form->get('numero')->getData());
    //         $association->setObjet($form->get('objet')->getData());
    //         $association->setSiegeSocial($form->get('siegeSocial')->getData());
    //         $association->setDateCreation($form->get('dateCreation')->getData());
    //         $association->setLienLogo($form->get('lienLogo')->getData());
    //         $association->setRecipisse($form->get('recipisse')->getData());

    //         $this->entityManager->persist($association);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_association');
    //     }

    //     return $this->render('association/add.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updateassociation' , name: 'UpdateAssociation')]
    // public function UpdateAssociation(Request $request)
    // {
    //     $association = $this->associationRepository->find(1);

    //     $form = $this->createForm(AssociationFormType::class, $association);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $association->setNom($form->get('nom')->getData());
    //         $association->setNumero($form->get('numero')->getData());
    //         $association->setObjet($form->get('objet')->getData());
    //         $association->setSiegeSocial($form->get('siegeSocial')->getData());
    //         $association->setDateCreation($form->get('dateCreation')->getData());
    //         $association->setLienLogo($form->get('lienLogo')->getData());
    //         $association->setRecipisse($form->get('recipisse')->getData());

    //         $this->entityManager->persist($association);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_association');
    //     }

    //     return $this->render('association/update.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/addassociationdocument', name:'AddAssociationDocument')]
    // public function AddDocument(Request $request, )
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

    //         $association = $this->associationRepository->find(1);

    //         $association->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_association');
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/association/deletedocument/{id}', name:'DeleteAssociationDocument')]
    // public function DeleteAssociationDocument($id)
    // {
    //     $document = $this->documentRepository->find($id);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_association');
    // }
}
