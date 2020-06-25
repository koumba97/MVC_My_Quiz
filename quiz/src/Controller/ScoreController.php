<?php

namespace App\Controller;

use App\Entity\Score;
use App\Entity\Categorie;
use App\Entity\Reponse;
use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class ScoreController extends AbstractController
{
    /*
    * @var QuestionRepository
    */
    private $repository;
    private $session;

    /**
     * @var Security
     */
    private $security;

    public function __construct(ScoreRepository $repository, SessionInterface $session, Security $security){
        $this->repository = $repository;
        $this->session = $session;
        $this->security = $security;
    }


    /**
     * @Route("/score", name="score")
     */
    public function index()
    {
        return $this->render('score/index.html.twig', [
            'controller_name' => 'ScoreController',
        ]);
    }


    /**
     * @Route("/quiz/{categorie}/score", name="score_end")
     */
    public function score(Request $request, $categorie){

        dump($this->session->all());
        $good_answers=array();
        for($i=1; $i<11; $i++){
            if ($i == 10){

                $i = 0;
                $reponseId = $this->getDoctrine()->getRepository(Reponse::Class)->findOneBy(['id_question' => ($categorie).$i, 'reponse_expected' => 1 ]);
                array_push($good_answers, $reponseId->getReponseExpected());
                break;                
            }
            $reponseId = $this->getDoctrine()->getRepository(Reponse::Class)->findOneBy(['id_question' => ($categorie-1).$i, 'reponse_expected' => 1 ]);
            array_push($good_answers, $reponseId->getReponseExpected());
        }

        $result=0; $y=1;
        for($i=0; $i<count($good_answers); $i++){

            if ($this->session->get($y) == $good_answers[$i]){
                $result++;
            }
            $y++;
        }

        $categorieDetails = $this->getDoctrine()->getRepository(Categorie::Class)->findAll()[$categorie-1];
        $image = $categorieDetails->getPicture();
        $name = $categorieDetails->getName();
        
        $note = "$result/10";


        $this->session->set("score", "$note");
        $this->session->set("categorie_id", $categorie);
        return $this->render('quiz/score.html.twig', [
            'note' => $note,
            'categorie' => $name,
            'image' => $image
        ]);

    }   

    /**
     * @Route("/quiz/save", name="save")
     */
    public function save(Request $request){
        dump($this->session->all());

        // si inscrit
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $player_role = $user->getId();
            $playername = $user->getUsername();

        }
        //si invité
        else{
            $playername = $this->session->get('playerName');
            $player_role = "guest";
        }

        $entityManager = $this->getDoctrine()->getManager();


        $categorie = $this->session->get('categorie_id');
        $note = $this->session->get('score');

        dump($note);
        $score = new Score();
        $score->setPlayerRole($player_role);
        $score->setPlayername($playername);
        $score->setScore($note);
        $score->setCategorie($categorie);
        $score->setDate(new \DateTime());

        $entityManager->persist($score);
        $entityManager->flush();
        return $this->redirectToRoute('score');
    }
}
