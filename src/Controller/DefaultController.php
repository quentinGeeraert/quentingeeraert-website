<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ExtDatabase\Contact;
use App\Entity\ProjectPortfolio;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\ArticleRepository;
use App\Repository\ExperienceRepository;
use App\Repository\ProjectPortfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage", methods={"GET"})
     */
    public function homepage(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(['is_online' => true], ['created_at' => 'DESC'], 5);

        return $this->render('default/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("articles/{slug?}", name="app_articles", methods={"GET"})
     *
     * @param string|null $slug
     */
    public function article($slug = null, ArticleRepository $articleRepository): Response
    {
        if ($slug) {
            $article = $articleRepository->findOneBy(['slug' => $slug, 'is_online' => true]);
            if ($article instanceof Article) {
                return $this->render('default/articles/show.html.twig', [
                    'article' => $article,
                    'h1' => $article->getTitle(),
                ]);
            }
            throw $this->createNotFoundException('No article found for slug '.$slug);
        }

        $articles = $articleRepository->findBy(['is_online' => true], ['created_at' => 'DESC']);

        return $this->render('default/articles/index.html.twig', [
            'h1' => 'Articles',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("experiences", name="app_experiences", methods={"GET"})
     */
    public function experience(ExperienceRepository $experienceRepository): Response
    {
        $experiences = $experienceRepository->findBy(['user' => 1]);

        return $this->render('default/experiences.html.twig', [
            'experiences' => $experiences,
        ]);
    }

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
                return $this->render('default/portfolio/show.html.twig', [
                    'projectPortfolio' => $projectPortfolio,
                    'h1' => $projectPortfolio->getName(),
                ]);
            }
            throw $this->createNotFoundException('No project portfolio found for slug '.$slug);
        }

        return $this->render('default/portfolio/index.html.twig', [
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
