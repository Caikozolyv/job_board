<?php

namespace App\Controller;

use App\DTO\Table\DataTableWebsite;
use App\Entity\Website;
use App\Form\WebsiteType;
use App\Repository\WebsiteRepository;
use App\Utils\FlashMessages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/websites')]
final class WebsiteController extends AbstractController
{
    private const CLASS_SHORT_NAME = 'websites';


    #[Route(name: 'app_website_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('table.html.twig', [
            'objectName' => self::CLASS_SHORT_NAME,
        ]);
    }

    #[Route('/new', name: 'app_website_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $website = new Website();
        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($website);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 0);

            return $this->redirect($request->getUri());
        }

        return $this->render('website/new.html.twig', [
            'website' => $website,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_website_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Website $website, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 1);

            return $this->redirectToRoute('app_website_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website/edit.html.twig', [
            'website' => $website,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_website_delete', methods: ['POST'])]
    public function delete(Request $request, Website $website, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$website->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($website);
            $entityManager->flush();

            FlashMessages::displayActionMessage(self::CLASS_SHORT_NAME, 2);
        }

        return $this->redirectToRoute('app_website_index', [], Response::HTTP_SEE_OTHER);
    }
}
