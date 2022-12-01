<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Personne;
use App\Form\PersonneFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ContactController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ContactRepository $contactRepository, private SerializerInterface $serializerInterface){}

    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        $contact = $this->contactRepository->findAll();

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contacts' => $contact,
        ]);
    }

    #[Route('apis/contacts', name:'APICreateContact', methods:['POST'])]
    public function APICreateContact(Request $request)
    {
        $contact = $this->serializerInterface->deserialize($request->getContent(), Contact::class, 'json');

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $contact = $this->serializerInterface->serialize($contact, 'json');

        return new JsonResponse($contact, Response::HTTP_OK, [], true);
    }

    #[Route('apis/contacts', name:'APIGetAllContacts', methods:['GET'])]
    public function APIGetAllContacts()
    {
        $contacts = $this->contactRepository->findAll();

        $contacts = $this->serializerInterface->serialize($contacts, 'json');

        return new JsonResponse($contacts, Response::HTTP_OK, [], true);
    }

    #[Route('apis/contacts/{id}', name:'APIGetContact', methods:['GET'])]
    public function APIGetContact($id)
    {
        $contact = $this->contactRepository->find($id);

        $contact = $this->serializerInterface->serialize($contact, 'json');

        return new JsonResponse($contact, Response::HTTP_OK, [], true);
    }

    #[Route('apis/contacts/{id}', name:'APIUpdateContact', methods:['PUT'])]
    public function APIUpdateContact(Request $request, $id)
    {
        $contact = $this->contactRepository->find($id);

        $update = $this->serializerInterface->deserialize($request->getContent(), Contact::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contact]);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $contact = $this->serializerInterface->serialize($update, 'json');

        return new JsonResponse($contact, Response::HTTP_OK, [], true);
    }

    #[Route('apis/contacts/{id}', name:'APIDeleteContact', methods:['DELETE'])]
    public function APIDeleteContact($id)
    {
        $this->entityManager->remove($this->contactRepository->find($id));
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    // #[Route('/addcontact', name:'AddContact')]
    // public function AddContact(Request $request)
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

    //         $contact = new Contact();

    //         $contact->setPersonne($personne);

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($contact);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_contact');
    //     }

    //     return $this->render('contact/addcontact.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatecontact/{id}', name:'UpdateContact')]
    // public function UpdateContact(Request $request , $id)
    // {
    //     $contact = $this->contactRepository->find($id);
    //     $personne = $contact->getPersonne();

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

    //         $contact->setPersonne($personne);

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_contact');
    //     }

    //     return $this->render('contact/updatecontact.html.twig' , [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletecontact/{id}' , name:'DeleteContact')]
    // public function DeleteContact($id)
    // {
    //     $contact = $this->contactRepository->find($id);

    //     $this->entityManager->remove($contact);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_contact');
    // }
}
