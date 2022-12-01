<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Personne;
use App\Form\PersonneFormType;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class NewsletterController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private NewsletterRepository $newsletterRepository, private SerializerInterface $serializerInterface){}

    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(): Response
    {
        $newsletters = $this->newsletterRepository->findAll();
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
            'newsletters' => $newsletters,
        ]);
    }

    #[Route('apis/newsletters', name:'APICreateNewsletter', methods:['POST'])]
    public function APICreateNewsletter(Request $request)
    {
        $newsletter = $this->serializerInterface->deserialize($request->getContent(), Newsletter::class, 'json');

        $this->entityManager->persist($newsletter);
        $this->entityManager->flush();

        $newsletter = $this->serializerInterface->serialize($newsletter, 'json');

        return new JsonResponse($newsletter, Response::HTTP_CREATED, [], true);
    }

    #[Route('apis/newsletters', name:'APIGetAllNewsletters', methods:['GET'])]
    public function APIGetAllNewsLetter()
    {
        $newsletters = $this->newsletterRepository->findAll();

        $newsletters = $this->serializerInterface->serialize($newsletters, 'json');

        return new JsonResponse($newsletters, Response::HTTP_OK, [], true);
    }

    #[Route('apis/newsletters/{id}', name:'APIGetNewsletter', methods:['GET'])]
    public function APIGetNewsLetter($id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        $newsletter = $this->serializerInterface->serialize($newsletter, 'json');

        return new JsonResponse($newsletter, Response::HTTP_OK, [], true);
    }

    #[Route('apis/newsletters/{id}', name:'APIUpdateNewsletter', methods:['PUT'])]
    public function APIUpdateNewsletter(Request $request, $id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        $update = $this->serializerInterface->deserialize($request->getContent(), Newsletter::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $newsletter]);

        $this->entityManager->persist($update);
        $this->entityManager->flush();

        $newsletter = $this->serializerInterface->serialize($update, 'json');

        return new JsonResponse($newsletter, Response::HTTP_OK, [], true);
    }

    #[Route('apis/newsletters/{id}', name:'APIDeleteNewsletter', methods:['DELETE'])]
    public function APIDeleteNewsletter($id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        $this->entityManager->remove($newsletter);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // #[Route('addnewsletter', name:'AddNewsletter')]
    // public function AddNewsletter(Request $request)
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

    //         $newsletter = new Newsletter();

    //         $newsletter->setPersonne($personne);

    //         $this->entityManager->persist($personne);
    //         $this->entityManager->persist($newsletter);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_newsletter');
    //     } 

    //     return $this->render('newsletter/addnewsletter.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/updatenewsletter/{id}', name:'UpdateNewsletter')]
    // public function UpdateNewsletter(Request $request, $id)
    // {
    //     $newsletter = $this->newsletterRepository->find($id);
    //     $personne = $newsletter->getPersonne();

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

    //         $newsletter->setPersonne($personne);

    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('app_newsletter');
    //     } 

    //     return $this->render('newsletter/updatenewsletter.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/deletenewsletter/{id}', name:'DeleteNewsletter')]
    // public function DeleteNewsletter($id)
    // {
    //     $newsletter = $this->newsletterRepository->find($id);

    //     $this->entityManager->remove($newsletter);
    //     $this->entityManager->flush();

    //     return $this->redirectToRoute('app_newsletter');
    // }
}
