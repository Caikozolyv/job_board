<?php

namespace App\Controller;

use App\DTO\Table\DataTableAction;
use App\DTO\Table\TableDTO;
use App\Entity\Action;
use App\Form\ActionType;
use App\Repository\ActionRepository;
use App\Utils\FlashMessages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/actions')]
final class ActionController extends AbstractController
{
    private const CLASS_SHORT_NAME = 'action';

    #[Route(name: 'app_action_index', methods: ['GET'])]
    public function index(ActionRepository $actionRepository): Response
    {
        $actions = $actionRepository->findAll();

        $actionData = new DataTableAction();
        $dto = new TableDTO($actionData);
        $formattedActions = $dto->mergeArrays($actions);

        return $this->render('table.html.twig', [
            'datas' => $formattedActions,
        ]);
    }

    #[Route('/new', name: 'app_action_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $action = new Action();
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($action);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 0);

            return $this->redirect($request->getUri());
        }

        return $this->render('action/new.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_action_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Action $action, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 1);

            return $this->redirectToRoute('app_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('action/edit.html.twig', [
            'action' => $action,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_action_delete', methods: ['POST'])]
    public function delete(Request $request, Action $action, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$action->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($action);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 2);
        }

        return $this->redirectToRoute('app_action_index', [], Response::HTTP_SEE_OTHER);
    }
}
