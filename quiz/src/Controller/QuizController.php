<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz")
     */
    public function index(Request $request)
    {
        $playerName = $request->get('playername');
        
        $categorie = $this->getDoctrine('categorie')
        ->getRepository('AppBundle:Categorie');

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }

        // return $this->render('quiz/index.html.twig', [
        //     'controller_name' => 'QuizController',
        // ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('quiz/home.html.twig');
    }

}
