<?php
// src/Controller/ProgramController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Services\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Repository\CommentRepository;

/**
 * @Route("/programs", name="program_")
 */

class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * 
     * @Route("/", name="index")
     * @return Response  A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs
        ]);
    }


    /**
     * The controller for the program add form
     * @Route("/new", name="new")
     * @return Response 
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('kevinroig@hotmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
      }
      return $this->render('program/new.html.twig', [
          "form" => $form->createView(),
      ]);

    }

    /**
     * Getting a program by id
     * @Route("/{slug}", name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {    
        return $this->render('program/show.html.twig', [
                'program' => $program
            ]);
    }

    /**
     * Show season of a program
     * @Route("/{slug}/season/{season}", name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * Show episode 
     * @Route("/{programSlug}/season/{season}/episode/{episodeSlug}", name="episode_show")
     * @ParamConverter("program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("episode", options={"mapping": {"episodeSlug": "slug"}})
     */
    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setEpisode($episode);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }

        $comments = $commentRepository->findBy(['episode' => $episode]);
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/{id}/edit", name="program_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="program_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index');
    }
}