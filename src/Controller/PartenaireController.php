<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Partenaire;
use App\Entity\Personne;
use App\Form\DocumentFormType;
use App\Form\PersonneFormType;
use App\Repository\DocumentRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PartenaireController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private PartenaireRepository $partenaireRepository, private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/partenaire', name: 'app_partenaire')]
    public function index(): Response
    {
        $partenaires = $this->partenaireRepository->findAll();

        return $this->render('partenaire/index.html.twig', [
            'controller_name' => 'PartenaireController',
            'partenaires' => $partenaires,
        ]);
    }

    #[Route('apis/partenaires', name:'APICreatePartenaire', methods:['POST'])]
    public function APICratePartenaire(Request $request)
    {
        $partenaire = $this->serializerInterface->deserialize($request->getContent(), Partenaire::class, 'json');

        $this->entityManager->persist($partenaire);
        $this->entityManager->flush();

        $partenaire = $this->serializerInterface->serialize($partenaire, 'json', ['groups' => 'getPartenaire']);

        return new JsonResponse($partenaire, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/partenaires', name:'APIGetAllPartenaires', methods:['GET'])]
    public function APIGetAllPartenaires()
    {
        $partenaires = $this->partenaireRepository->findAll();

        $partenaires = $this->serializerInterface->serialize($partenaires, 'json', ['groups' => 'getPartenaire']);

        return new JsonResponse($partenaires, Response::HTTP_OK, [], true);
    }

    #[Route('apis/partenaires/{id}', name:'APIGetPartenaire', methods:['GET'])]
    public function APIGetPartenaire($id)
    {
        $partenaire = $this->partenaireRepository->find($id);

        $partenaire = $this->serializerInterface->serialize($partenaire, 'json', ['groups' => 'getPartenaire']);

        return new JsonResponse($partenaire, Response::HTTP_OK, [], true);
    }

    #[Route('apis/partenaires/{id}', name:'APIUpdatePartenaire', methods:['PUT'])]
    public function APIUpdatePartenaire(Request $request, $id)
    {
        $partenaire = $this->partenaireRepository->find($id);
        $documents = $partenaire->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $update = $this->serializerInterface->deserialize($request->getContent(), Partenaire::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $partenaire]);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $partenaire = $this->serializerInterface->serialize($update, 'json', ['groups' => 'getPartenaire']);

        return new JsonResponse($partenaire, Response::HTTP_OK, [], true);
    }

    #[Route('apis/partenaires/{id}', name:'APIDeletePartenaire', methods:['DELETE'])]
    public function APIDeletePartenaire($id)
    {
        $partenaire = $this->partenaireRepository->find($id);
        $documents = $partenaire->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $this->entityManager->remove($partenaire);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/addpartenaire', name:'AddPartenaire')]
    // public function AddPartenaire(Request $request)
    // {
    //     $personne = new Personne();

    //     $form = $this->createForm(PersonneFormType::class, $personne);

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

    //         $partenaire = new Partenaire();
    //         $partenaire->setPersonne($personne);
    //         $partenaire->setUrlSite($_POST['urlsite']);

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($partenaire);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_partenaire');
    //     }

    //     return $this->render('partenaire/addpartenaire.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatepartenaire/{id}', name:'UpdatePartenaire')]
    // public function UpdatePartenaire(Request $request, $id)
    // {
    //     $partenaire = $this->partenaireRepository->find($id);
    //     $personne = $partenaire->getPersonne();

    //     $form = $this->createForm(PersonneFormType::class, $personne);

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

    //         $partenaire->setPersonne($personne);
    //         $partenaire->setUrlSite($_POST['urlsite']);

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_partenaire');
    //     }

    //     return $this->render('partenaire/updatepartenaire.html.twig', [
    //         'form' => $form->createView(),
    //         'part' => $partenaire,
    //     ]);
    // }

    // #[Route('/deletepartenaire/{id}', name:'DeletePartenaire')]
    // public function DeletePartenaire($id)
    // {
    //     $partenaire = $this->partenaireRepository->find($id);

    //     $this->entityManager->remove($partenaire);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_partenaire');
    // }

    // #[Route('/partenaire/{idp}/adddocument', name:'AddPartenaireDocument')]
    // public function AddPartenaireDocument(Request $request, $idp)
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

    //         $partenaire = $this->partenaireRepository->find($idp);

    //         $partenaire->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_partenaire');
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletepartenairedocument/{id}', name:'DeletePartenaireDocument')]
    // public function DeleteMembreDocument($id)
    // {
    //     $document = $this->documentRepository->find($id);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_partenaire');
    // }
}
