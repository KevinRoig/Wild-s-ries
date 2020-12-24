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
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/categories", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
    * @Route("/", name="index")
    *@return Reponse
    */
     public function index(CategoryRepository $category) 
     {
        return $this->render('category/index.html.twig', [
            'category' => $category->findAll()
        ]);
     }

        /**
       * The controller for the category add form
       * @Route("/new", name="new")
       */
      public function new(Request $request): Response
      {
          $category = new Category();
          $form = $this->createForm(CategoryType::class, $category);
          $form->handleRequest($request);
          if ($form->isSubmitted()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();
                return $this->redirectToRoute('category_index');
          }
          return $this->render('category/new.html.twig', [
              "form" => $form->createView(),
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