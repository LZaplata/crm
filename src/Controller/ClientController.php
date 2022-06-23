<?php


namespace App\Controller;


use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClientsController
 *
 * @package App\Controller
 *
 * @IsGranted("ROLE_SUPERADMIN")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/clients", name="client_list")
     *
     * @param ClientRepository $clientRepository
     *
     * @return Response
     */
    public function list(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findBy([], [
            "name" => "ASC"
        ]);

        return $this->render("client/list.html.twig", [
            "clients" => $clients
        ]);
    }

    /**
     * @Route("/client/add", name="client_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute("client_list");
        }

        return $this->renderForm("client/add.html.twig", [
            "form" => $form,
        ]);
    }

    /**
     * @Route("/client/{id}/edit", name="client_edit")
     *
     * @param Client $client
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Client $client, Request $request): Response
    {
        $form = $this->createForm(ClientType::class);
        $form->setData($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("client_list");
        }

        return $this->renderForm("client/edit.html.twig", [
            "form" => $form,
        ]);
    }
}