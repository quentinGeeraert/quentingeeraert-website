<?php

namespace App\Controller;

//  Entities
use App\Entity\Article;
use App\Entity\ExtDatabase\Contact;
use App\Entity\ProjectPortfolio;
use App\Entity\User;
// Forms
use App\Form\ContactType;
// Repositories
use App\Notification\ContactNotification;
use App\Repository\ArticleRepository;
use App\Repository\ExperienceRepository;
use App\Repository\ProjectPortfolioRepository;
// Notifications
use App\Repository\UserRepository;
// Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const QUENTIN_USER_ID = 1;

    /**
     * @Route("/", name="app_homepage", methods={"GET"})
     */
    public function homepage(ArticleRepository $articleRepository): Response
    {
        $limitArticlesInPage = 5;
        $articles = $articleRepository->findBy(['is_online' => true], ['created_at' => 'DESC'], $limitArticlesInPage);
        $buttonShowMoreArticles = ($articleRepository->count(['is_online' => true]) > $limitArticlesInPage);

        return $this->render('default/homepage.html.twig', compact('articles', 'buttonShowMoreArticles'));
    }

    /**
     * @Route("articles/{slug?}", name="app_articles", methods={"GET"})
     */
    public function article(?string $slug = null, ArticleRepository $articleRepository): Response
    {
        $h1 = 'Articles';
        if (null !== $slug) {
            $article = $articleRepository->findOneBy(['slug' => $slug, 'is_online' => true]);
            if ($article instanceof Article) {
                $h1 = $article->getTitle();

                return $this->render('default/articles/show.html.twig', compact('h1', 'article'));
            }
            throw $this->createNotFoundException('No article found for slug '.$slug);
        }
        $articles = $articleRepository->findBy(['is_online' => true], ['created_at' => 'DESC']);

        return $this->render('default/articles/index.html.twig', compact('h1', 'articles'));
    }

    /**
     * @Route("experiences", name="app_experiences", methods={"GET"})
     */
    public function experience(ExperienceRepository $experienceRepository): Response
    {
        $experiences = $experienceRepository->findBy(['user' => self::QUENTIN_USER_ID]);

        return $this->render('default/experiences.html.twig', compact('experiences'));
    }

    /**
     * @Route("skills", name="app_skills", methods={"GET"})
     */
    public function skill(UserRepository $userRepository): Response
    {
        $skills = [];
        $viewType = 'gallery';
        $user = $userRepository->findOneBy(['id' => self::QUENTIN_USER_ID]);
        if ($user instanceof User) {
            $skills = $user->getSkills();
        }

        return $this->render('default/skills.html.twig', compact('skills', 'viewType'));
    }

    /**
     * @Route("portfolio/{slug?}", name="app_portfolio", methods={"GET"})
     */
    public function portfolio(?string $slug = null, ProjectPortfolioRepository $projectPortfolioRepository): Response
    {
        $h1 = 'Portfolio';
        $viewType = 'gallery';
        if (null !== $slug) {
            $projectPortfolio = $projectPortfolioRepository->findOneBy(['slug' => $slug, 'is_online' => true]);
            if ($projectPortfolio instanceof ProjectPortfolio) {
                $h1 = $projectPortfolio->getName();

                return $this->render('default/portfolio/show.html.twig', compact('h1', 'projectPortfolio'));
            }
            throw $this->createNotFoundException('No project portfolio found for slug '.$slug);
        }
        $projectsPortfolio = $projectPortfolioRepository->findBy(['is_online' => true]);

        return $this->render('default/portfolio/index.html.twig', compact('h1', 'projectsPortfolio', 'viewType'));
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
