<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Message;
use App\Form\DocumentFormType;
use App\Form\MessageFormType;
use App\Repository\DocumentRepository;
use App\Repository\MessageRepository;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{    
    public function __construct(private EntityManagerInterface $entityManager, private MessageRepository $messageRepository, private DocumentRepository $documentRepository, private SerializerInterface $serializerInterface){}

    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        $messages = $this->messageRepository->findAll();

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
            'messages' => $messages,
        ]);
    }

    #[Route('apis/messages', name:'APICreateMessage', methods:['POST'])]
    public function APICreateMessage(Request $request)
    {
        $message = $this->serializerInterface->deserialize($request->getContent(), Message::class, 'json', ['groups' => 'getMessage']);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $message = $this->serializerInterface->serialize($message, 'json', ['groups' => 'getMessage']);

        return new JsonResponse($message, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/messages', name:'APIGetAllMessages', methods:['GET'])]
    public function APIGetAllMessages()
    {
        $messages = $this->messageRepository->findAll();

        $messages = $this->serializerInterface->serialize($messages, 'json', ['groups' => 'getMessage']);

        return new JsonResponse($messages, Response::HTTP_OK, [], true);
    }

    #[Route('apis/messages/{id}', name:'APIGetMessage', methods:['GET'])]
    public function APIGetMessages($id)
    {
        $message = $this->messageRepository->find($id);

        $message = $this->serializerInterface->serialize($message, 'json', ['groups' => 'getMessage']);

        return new JsonResponse($message, Response::HTTP_OK, [], true);
    }

    #[Route('apis/messages/{id}', name:'APIUpdateMessage', methods:['PUT'])]
    public function APIUpdateMessage(Request $request, $id)
    {
        $message = $this->messageRepository->find($id);
        $documents = $message->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $update = $this->serializerInterface->deserialize($request->getContent(), Message::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $message, 'groups' => 'getMessage']);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $message = $this->serializerInterface->serialize($update, 'json', ['groups' => 'getMessage']);

        return new JsonResponse($message, Response::HTTP_OK, [], true);
    }

    #[Route('apis/messages/{id}', name:'APIDeleteMessage', methods:['DELETE'])]
    public function APIDeleteMessage($id)
    {
        $message = $this->messageRepository->find($id);
        $documents = $message->getDocuments();

        foreach($documents as $docs)
        {
            $this->entityManager->remove($docs);
            $this->entityManager->flush();
        }

        $this->entityManager->remove($message);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('/addmessage', name:'AddMessage')]
    // public function AddMessage(Request $request)
    // {
    //     $message = new Message();

    //     $form = $this->createForm(MessageFormType::class, $message);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $message->setDate($form->get('date')->getData());
    //         $message->setSujet($form->get('sujet')->getData());
    //         $message->setContenu($form->get('contenu')->getData());
            
    //         $this->entityManager->persist($message);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_message');
    //     }

    //     return $this->render('message/addmessage.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatemessage/{id}', name:'UpdateMessage')]
    // public function UpdateMessage(Request $request, $id)
    // {
    //     $message = $this->messageRepository->find(($id));

    //     $form = $this->createForm(MessageFormType::class, $message);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $message->setDate($form->get('date')->getData());
    //         $message->setSujet($form->get('sujet')->getData());
    //         $message->setContenu($form->get('contenu')->getData());
            
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_message');
    //     }

    //     return $this->render('message/updatemessage.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletemessage/{id}', name:'DeleteMessage')]
    // public function DeleteMessage($id)
    // {
    //     $message = $this->messageRepository->find($id);

    //     $this->entityManager->remove($message);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_message');
    // }

    // #[Route('/message/{id}/adddocument', name:'AddMessageDocument')]
    // public function AddMessageDocument(Request $request, $id)
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

    //         $message = $this->messageRepository->find($id);

    //         $message->addDocument($document);

    //         $this->entityManager->persist($document);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_message');
    //     }

    //     return $this->render('document/adddocument.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletemessagedocument/{id}', name:'DeleteMessageDocument')]
    // public function DeleteMembreDocument($id)
    // {
    //     $document = $this->documentRepository->find($id);

    //     $this->entityManager->remove($document);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_message');
    // }
}
