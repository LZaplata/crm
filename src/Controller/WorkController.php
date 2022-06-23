<?php

namespace App\Controller;

use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkController extends AbstractController
{
    /**
     * @Route("/works", name="work_list")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param WorkRepository $workRepository
     * @param Request $request
     *
     * @return Response
     */
    public function list(WorkRepository $workRepository, Request $request): Response
    {
        $works = $workRepository->findAllOrdered($request->query->get("order", "work.id"), $request->query->get("dir", "asc"), $request->query->get("payed", false));

        return $this->render("work/list.html.twig", [
            "works" => $works
        ]);
    }

    /**
     * @Route("/work/add", name="work_add")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(WorkType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $work = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute("work_list");
        }

        return $this->renderForm("work/add.html.twig", [
            "form" => $form,
        ]);
    }

    /**
     * @Route("/work/{id}/edit", name="work_edit")
     *
     * @param Work $work
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Work $work, Request $request): Response
    {
        $form = $this->createForm(WorkType::class);
        $form->setData($work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $work = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("work_list");
        }

        return $this->renderForm("work/edit.html.twig", [
            "form" => $form,
        ]);
    }
}
