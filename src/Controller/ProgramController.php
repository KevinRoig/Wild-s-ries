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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
      }
      return $this->render('program/new.html.twig', [
          "form" => $form->createView(),
      ]);

    }

    /**
     * Getting a program by id
     * @Route("/show/{id}", name="show")
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
     * @Route("/{program}/season/{season}", name="season_show")
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
     * @Route("/{program}/season/{season}/episode/{episode}", name="episode_show")
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}