<?php

namespace App\Controller;

use App\Entity\Job;
use App\Enum\StatusEnum;
use App\Form\JobType;
use App\Repository\JobRepository;
use App\Utils\FlashMessages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/jobs')]
final class JobController extends AbstractController
{
    private const CLASS_SHORT_NAME = 'job';

    #[Route('/', name: 'default', methods: ['GET'])]
    public function defaultAction(): Response
    {
        return $this->redirectToRoute('app_job_index');
    }

    #[Route(name: 'app_job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        $jobs = $jobRepository->findAll();
        array_map(function (Job $job) {
            $job->getStatusText();
        }, $jobs);
        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    #[Route('/new', name: 'app_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setStatus(StatusEnum::Applied->value);
            $entityManager->persist($job);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 0);

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ?Job $job, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 1);

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(Request $request, ?Job $job, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($job);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 2);
        }

        return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
    }
}
