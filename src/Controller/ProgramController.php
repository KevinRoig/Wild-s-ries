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
use App\Repository\EpisodeRepository;
use PhpMyAdmin\Plugins\Schema\Eps\Eps;

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