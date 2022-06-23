<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Work;
use App\Form\LogType;
use App\Repository\LogRepository;
use App\Repository\WorkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    /**
     * @Route("/work/{id}/log", name="log_list")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Work $work
     * @param WorkRepository $workRepository
     * @param LogRepository $logRepository
     * @param Request $request
     *
     * @return Response
     */
    public function list(Work $work, WorkRepository $workRepository, LogRepository $logRepository, Request $request): Response
    {
        $works = $workRepository->findAllOrdered($request->query->get("order", "work.id"), $request->query->get("dir", "asc"), $request->query->get("payed", false));
        $log = $logRepository->findBy([
            "work" => $work->getId()
        ], [
            "created_at" => "DESC",
            "id" => "DESC"
        ]);

        return $this->render("log/list.html.twig", [
            "works" => $works,
            "work" => $work,
            "log" => $log,
        ]);
    }

    /**
     * @Route("/work/{id}/log/add", name="log_add")
     *
     * @param Work $work
     * @param Request $request
     * @param WorkRepository $workRepository
     * @param LogRepository $logRepository
     *
     * @return Response
     */
    public function add(Work $work, Request $request, WorkRepository $workRepository, LogRepository $logRepository): Response
    {
        $works = $workRepository->findAllOrdered($request->query->get("order", "work.id"), $request->query->get("dir", "asc"));
        $log = $logRepository->findBy([
            "work" => $work->getId()
        ], [
            "created_at" => "DESC",
            "id" => "DESC"
        ]);

        $form = $this->createForm(LogType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entry = $form->getData();
            $entry->setUser($this->getUser());
            $entry->setWork($work);

            if ($entry->getState()) {
                $work->setState($entry->getState());
            }

            if ($entry->getPrice()) {
                $work->setPrice($work->getPrice() + $entry->getPrice());
            }

            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($entry);
            $entityManger->flush();

            return $this->redirectToRoute("log_list", array_merge($request->query->all(), ["id" => $work->getId()]));
        }

        return $this->renderForm("log/add.html.twig", [
            "works" => $works,
            "work" => $work,
            "log" => $log,
            "form" => $form,
        ]);
    }

    /**
     * @Route("/work/{id}/log/{entry}/edit", name="log_edit")
     *
     * @param Work $work
     * @param Log $entry
     * @param Request $request
     * @param WorkRepository $workRepository
     * @param LogRepository $logRepository
     *
     * @return Response
     */
    public function edit(Work $work, Log $entry, Request $request, WorkRepository $workRepository, LogRepository $logRepository): Response
    {
        $works = $workRepository->findAllOrdered($request->query->get("order", "work.id"), $request->query->get("dir", "asc"));
        $log = $logRepository->findBy([
            "work" => $work->getId()
        ], [
            "created_at" => "DESC",
            "id" => "DESC"
        ]);

        $form = $this->createForm(LogType::class);
        $form->setData($entry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data->getState()) {
                $work->setState($data->getState());
            }

            if ($data->getPrice()) {
                if ($entry->getPrice() < $data->getPrice()) {
                    $work->setPrice($work->getPrice() + ($data->getPrice() - $entry->getPrice()));
                } else {
                    $work->setPrice($work->getPrice() - ($entry->getPrice() - $data->getPrice()));
                }
            }

            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->flush();

            return $this->redirectToRoute("log_list", array_merge($request->query->all(), ["id" => $work->getId()]));
        }

        return $this->renderForm("log/edit.html.twig", [
            "works" => $works,
            "work" => $work,
            "log" => $log,
            "form" => $form,
        ]);
    }
}
