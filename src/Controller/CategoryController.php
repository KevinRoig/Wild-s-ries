<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/categories", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
    * @Route("/", name="index")
    *@return Reponse
    */
     public function index() 
     {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

        return $this->render('category/index.html.twig', [
            'category' => $category
        ]);
     }

     /**
      * @Route("/{categoryName}", name="show")
      * @return Reponse
      */
      public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
      {
           $category = $categoryRepository->findBy(['name' => $categoryName]);
          if(!$categoryName) {
            throw $this->createNotFoundException(
                'No program with id : ' .$categoryName . 'found in program\'s table.'
            );
            }
        
            $programs = $programRepository->findBy(['category' => $category],['id' => 'DESC'], 3);
            return $this->render('category/show.html.twig', [
                'programs' => $programs
            ]);
      }
}