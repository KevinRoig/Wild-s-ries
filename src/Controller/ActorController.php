<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;

class ActorController extends AbstractController
{
    /**
     * @Route("/actor/{id}", name="actor")
     */
    public function show(Actor $actor): Response
    {
        return $this->render('program/actor.html.twig',[
            'actor' => $actor
        ]);
    }
}
