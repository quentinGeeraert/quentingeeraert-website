<?php

namespace App\Controller;

use App\Entity\ExtDatabase\Contact;
use App\Entity\ProjectPortfolio;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\ProjectPortfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("portfolio/{slug?}", name="app_portfolio", methods={"GET"})
     *
     * @param string|null $slug
     */
    public function portfolio($slug = null, ProjectPortfolioRepository $projectPortfolioRepository): Response
    {
        if ($slug) {
            $projectPortfolio = $projectPortfolioRepository->findOneBy(['slug' => $slug, 'is_online' => true]);
            if ($projectPortfolio instanceof ProjectPortfolio) {
                return $this->render('default/portfolio_project.html.twig', [
                    'projectPortfolio' => $projectPortfolio,
                    'h1' => $projectPortfolio->getName(),
                ]);
            }
            throw $this->createNotFoundException('No project portfolio found for slug '.$slug);
        }

        return $this->render('default/portfolio.html.twig', [
            'projects' => $projectPortfolioRepository->findOnline(),
            'type' => 'gallery',
        ]);
    }

    /**
     * @Route("contact", name="app_contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, ContactNotification $contactNotification): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactNotification->notifyEmail($contact);
            $this->addFlash('success', 'Your request has been sent');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
