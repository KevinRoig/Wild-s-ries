<?php
// src/Controller/ProgramController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Repository\SeasonRepository;
use App\Entity\Season;
use app\entity\Episode;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use PhpMyAdmin\Plugins\Schema\Eps\Eps;
use PhpMyAdmin\Tests\Stubs\Response as StubsResponse;
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
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
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
     * @Route("/{programId}/season/{seasonId}", name="season_show")
     */
    public function showSeason(int $programId, int $seasonId, SeasonRepository $seasonRepository): Response
    {
        $seasonInfos = $seasonRepository->findOneBy(['id' => $seasonId]);
        return $this->render('program/season_show.html.twig', [
            'programId' => $programId,
            'seasonId' => $seasonId,
            'season' => $seasonInfos,
        ]);
    }

    /**
     * Show episode 
     * @Route("/{programId}/season/{seasonId}/episode/{episodeId}", name="episode_show")
     */
    public function showEpisode(int $programId, int $seasonId, int $episodeId, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
  
        $seasonInfos = $seasonRepository->find(['id' => $seasonId]);
        $episodeInfos = $episodeRepository->find(['id' => $episodeId]);
        return $this->render('program/episode_show.html.twig', [
            'programId' => $programId,
            'seasonId' => $seasonId,
            'episode' => $episodeInfos,
            'season' => $seasonInfos
        ]);
    }
}