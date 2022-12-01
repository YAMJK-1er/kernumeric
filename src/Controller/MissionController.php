<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Mission;
use App\Form\DocumentFormType;
use App\Form\MissionFormType;
use App\Repository\DocumentRepository;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MissionController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MissionRepository $missionRepository, private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/mission', name: 'app_mission')]
    public function index(): Response
    {
        $missions = $this->missionRepository->findAll();
        return $this->render('mission/index.html.twig', [
            'controller_name' => 'MissionController',
            'missions' => $missions,
        ]);
    }

    

    // #[Route('/addmission' , name:'AddMission')]
    // public function AddMission(Request $request)
    // {
    //     $mission = new Mission();

    //     $form = $this->createForm(MissionFormType::class, $mission);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $mission->setTitre($form->get('titre')->getData());
    //         $mission->setDescription($form->get('description')->getData());

    //         $this->entityManager->persist($mission);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_mission');
    //     }

    //     return $this->render('mission/addmission.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatemission/{id}' , name:'UpdateMission')]
    // public function UpdateMission(Request $request, $id)
    // {
    //     $mission = $this->missionRepository->find($id);

    //     $form = $this->createForm(MissionFormType::class, $mission);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $mission->setTitre($form->get('titre')->getData());
    //         $mission->setDescription($form->get('description')->getData());

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_mission');
    //     }

    //     return $this->render('mission/updatemission.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletemission/{id}', name:'DeleteMission')]
    // public function DeleteMission($id)
    // {
    //     $mission = $this->missionRepository->find($id);

    //     $this->entityManager->remove($mission);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_mission');
    // }

    // #[Route('/mission/{id}/adddocument', name:'AddMissionDocument')]
    // public function AddMissionDocument(Request $request, $id)
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

    //         $mission = $this->missionRepository->find($id);

    //         $mission->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_mission');
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletemissiondocument/{id}', name:'DeleteMissionDocument')]
    // public function DeleteMissionDocument($id)
    // {
    //     $document = $this->documentRepository->find($id);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_mission');
    // }
}
