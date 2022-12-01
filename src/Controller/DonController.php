<?php

namespace App\Controller;

use App\Entity\Don;
use App\Repository\DonateurRepository;
use App\Repository\DonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DonController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private DonRepository $donRepository, private DonateurRepository $donateurRepository, private SerializerInterface $serializerInterface){}

    #[Route('/don', name: 'app_don')]
    public function index(): Response
    {
        return $this->render('don/index.html.twig', [
            'controller_name' => 'DonController',
        ]);
    }

    #[Route('apis/donateurs/{id}/dons', name:'APICreateDon', methods:['POST'])]
    public function APICreateDon(Request $request, $id)
    {
        $donateur = $this->donateurRepository->find($id);

        $don = $this->serializerInterface->deserialize($request->getContent(), Don::class, 'json');

        $donateur->addDon($don);
        $don->setDonateur($donateur);

        $this->entityManager->persist($don);
        $this->entityManager->flush();

        $don = $this->serializerInterface->serialize($don, 'json', ['groups' => 'getDon']);
        
        return new JsonResponse($don, Response::HTTP_CREATED, ['groups' => 'getDon'], true);
    }

    #[Route('apis/dons', name:'APIGetAllDons', methods:['GET'])]
    public function APIGetAllDons()
    {
        $dons = $this->donRepository->findAll();

        $dons = $this->serializerInterface->serialize($dons, 'json', ['groups' => 'getDon']);

        return new JsonResponse($dons, Response::HTTP_OK, [], true);
    }

    #[Route('apis/dons/{id}', name:'APIGetDon', methods:['GET'])]
    public function APIGetDon($id)
    {
        $don = $this->donRepository->find($id);

        $don = $this->serializerInterface->serialize($don, 'json', ['groups' => 'getDon']);

        return new JsonResponse($don, Response::HTTP_OK, [], true);
    }
}
