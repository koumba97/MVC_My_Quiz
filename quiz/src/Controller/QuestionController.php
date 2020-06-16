<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Categorie;
use App\Entity\Reponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuestionRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuestionController extends AbstractController
{
    /**
     * @var QuestionRepository
     */
    private $repository;

    public function __construct(QuestionRepository $repository){
        $this->repository = $repository;
    }


    /**
     * @Route("/quiz/{categorie}/{id}", name="quiz")
     */
    public function index(Request $request, $categorie, $id) : Response
    {
        $unite=$categorie-1;
        $categorieName = $this->getDoctrine()->getRepository(Categorie::Class)->findOneBy(['id' => $categorie])->getName();
        $question = $this->repository->findOneBy(['id_categorie' => $categorie, 'id' => $unite.$id])->getQuestion();

        $reponses = $this->getDoctrine()->getRepository(Reponse::Class)->findBy(['id_question' => $unite.$id]);

        $choix1 = $reponses[0]->getReponse();
        $choix2 = $reponses[1]->getReponse();
        $choix3 = $reponses[2]->getReponse();
        
        return $this->render('quiz/question.html.twig', [
            'question' => $question,
            'question_number' => $id,
            'categorie' => $categorieName,
            'choixUn' => $choix1,
            'choixDeux' => $choix2,
            'choixTrois' => $choix3,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('quiz/home.html.twig');
    }

}
