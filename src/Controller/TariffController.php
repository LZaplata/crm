<?php

namespace App\Controller;

use App\Entity\Tariff;
use App\Form\TariffType;
use App\Repository\TariffRepository;
use App\Repository\UnitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TariffController extends AbstractController
{
    /**
     * @Route("/tariffs", name="tariff_list")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param TariffRepository $tariffRepository
     *
     * @return Response
     */
    public function list(TariffRepository $tariffRepository): Response
    {
        $tariffs = $tariffRepository->findAll();
        $tariffsUnits = $tariffRepository->findAllUnitsSummarized();

        return $this->render("tariff/list.html.twig", [
            "tariffs" => $tariffs,
            "tariffs_units" => $tariffsUnits,
        ]);
    }

    /**
     * @Route("/tariff/add", name="tariff_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(TariffType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tariff = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tariff);
            $entityManager->flush();

            return $this->redirectToRoute("tariff_list");
        }

        return $this->renderForm("tariff/add.html.twig", [
            "form" => $form,
        ]);
    }

    /**
     * @Route("/tariff/{id}/edit", name="tariff_edit")
     *
     * @param Tariff $tariff
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Tariff $tariff, Request $request): Response
    {
        $form = $this->createForm(TariffType::class);
        $form->setData($tariff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tariff = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("tariff_list");
        }

        return $this->renderForm("tariff/edit.html.twig", [
            "form" => $form,
        ]);
    }

    public function f()
    {

    }
}
