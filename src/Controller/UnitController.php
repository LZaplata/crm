<?php

namespace App\Controller;

use App\Entity\Tariff;
use App\Form\UnitType;
use App\Repository\TariffRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnitController extends AbstractController
{
    /**
     * @Route("/tariff/{id}/units", name="unit_list")
     *
     * @param Tariff $tariff
     * @param TariffRepository $tariffRepository
     * @param UnitRepository $unitRepository
     *
     * @return Response
     */
    public function list(Tariff $tariff, TariffRepository $tariffRepository, UnitRepository $unitRepository): Response
    {
        $tariffs = $tariffRepository->findAll();
        $tariffsUnits = $tariffRepository->findAllUnitsSummarized();
        $units = $unitRepository->findBy([
            "tariff" => $tariff->getId(),
        ], [
            "created_at" => "DESC",
            "id" => "DESC",
        ]);

        return $this->render("unit/list.html.twig", [
            "tariffs" => $tariffs,
            "tariffs_units" => $tariffsUnits,
            "tariff" => $tariff,
            "units" => $units,
        ]);
    }

    /**
     * @Route("/tariff/{id}/unit/add", name="unit_add")
     *
     * @param Tariff $tariff
     * @param Request $request
     * @param TariffRepository $tariffRepository
     * @param UnitRepository $unitRepository
     *
     * @return Response
     */
    public function add(Tariff $tariff, Request $request, TariffRepository $tariffRepository, UnitRepository $unitRepository): Response
    {
        $tariffs = $tariffRepository->findAll();
        $tariffsUnits = $tariffRepository->findAllUnitsSummarized();
        $units = $unitRepository->findBy([
            "tariff" => $tariff->getId(),
        ], [
            "created_at" => "DESC",
            "id" => "DESC",
        ]);

        $form = $this->createForm(UnitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit = $form->getData();
            $unit->setUser($this->getUser());
            $unit->setTariff($tariff);

            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($unit);
            $entityManger->flush();

            return $this->redirectToRoute("unit_list", ["id" => $tariff->getId()]);
        }

        return $this->renderForm("unit/add.html.twig", [
            "tariffs" => $tariffs,
            "tariffs_units" => $tariffsUnits,
            "tariff" => $tariff,
            "units" => $units,
            "form" => $form,
        ]);
    }
}
