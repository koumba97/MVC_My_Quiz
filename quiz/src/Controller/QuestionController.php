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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuestionController extends AbstractController
{
    /**
     * @var QuestionRepository
     */
    private $repository;
    private $session;

    public function __construct(QuestionRepository $repository, SessionInterface $session){
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        dump($this->session);
        return $this->render('quiz/home.html.twig');
    }

    /**
     * @Route("/quiz/{categorie}/{id}/{change}/", name="quiz")
     */
    public function index(Request $request, $categorie, $id, $change, SessionInterface $session ) : Response
    {
        $categorieName = $this->getDoctrine()->getRepository(Categorie::Class)->findOneBy(['id' => $categorie])->getName();
        if ($change == "out"){
            ?>
           <div class='fond'></div>
            <div class='pop'>
                <p>Tu es sur le point de changer de catégorie. <br/>Si tu changes de catégorie, ton avancé sur le quiz <span><?php echo $categorieName; ?></span> sera perdu !</p>
                <div class='buttons'>
                    <a href='../in/'><div class='button'>Finir ma partie</div></a>
                    <a href='../../../../categorie'><div class='button'>Abandonner</div></a>
                </div>
            </div>
            <?php
            
        
        }
        else{
           
        
        }
        $dizaine=$categorie-1;
        $unite = $id;
        if($id == 10){
            $dizaine = $categorie;
            $unite= 0;
        }
        else if ($id>10){
            // s'il  n'y a plus de question
            return $this->redirect('../../score');
        }
        else if ($id>9 && $categorie == 11){
            // s'il  n'y a plus de question dans la catégorie 'sigle info'
            return $this->redirect('../../quiz/score');
        }

        
        $categorieId = $this->getDoctrine()->getRepository(Categorie::Class)->findOneBy(['id' => $categorie])->getId();
        $question = $this->repository->findOneBy(['id_categorie' => $categorie, 'id' => $dizaine.$unite])->getQuestion();
        $count_question = count($this->repository->findBy(['id_categorie' => $categorie]));
        
        if($id == 10){
            $pourcentage = 100;
        }

        else{
            $pourcentage = 100 * $id / $count_question;
        }

        dump($this->session->all());

        $reponses = $this->getDoctrine()->getRepository(Reponse::Class)->findBy(['id_question' => $dizaine.$unite ]);
        $reponse2 = new Reponse();
    
        $choix1 = $reponses[0]->getReponse();
        $choix2 = $reponses[1]->getReponse();
        $choix3 = $reponses[2]->getReponse();
       

        return $this->render('quiz/question.html.twig', [
            'question' => $question,
            'pourcentage' => $pourcentage,
            'question_number' => $id,
            'categorie' => $categorieName,
            'id_categorie' => $categorieId,
            'choixUn' => $choix1,
            'choixDeux' => $choix2,
            'choixTrois' => $choix3,
        ]);
    }

    /**
     * @Route("/save_answer", name="save_answer")
     */
    public function save_answer(Request $request, SessionInterface $session){

        dump($request);

        $categorie = $request->get('categorie');
        $id_categorie = $request->get('id_categorie');
        $question = $request->get('id_question');
        $reponse = $request->get('choice');
        $next_question = $question+1;

    
        if($request->get('categorie')!== NULL){

            if ($request->get('categorie')== $categorie){
                $this->session->set("categorie", $categorie);
                $this->session->set($question, $reponse);
                
                return $this->redirect("quiz/$id_categorie/$next_question/in");
            }
            else{
                echo "Tu es sur le point de changer de catégorie. Si tu changes de catégorie, ton avancé sur le quiz $categorie sera perdu";
            }
        }
        
        
        
    }

     /**
     * @Route("/create", name="create")
     */
    public function create(){
        dump($this->session);
        return $this->render('quiz/create.html.twig');
    }

    //  /**
    //  * @Route("/quiz/{categorie}/score", name="score")
    //  */
    // public function score(Request $request, $categorie){

    //     dump($this->session->all());
    //     $good_answers=array();
    //     for($i=1; $i<11; $i++){
    //         if ($i == 10){

    //             $i = 0;
    //             $reponseId = $this->getDoctrine()->getRepository(Reponse::Class)->findOneBy(['id_question' => ($categorie).$i, 'reponse_expected' => 1 ]);
    //             array_push($good_answers, $reponseId->getReponseExpected());
    //             break;                
    //         }
    //         $reponseId = $this->getDoctrine()->getRepository(Reponse::Class)->findOneBy(['id_question' => ($categorie-1).$i, 'reponse_expected' => 1 ]);
    //         array_push($good_answers, $reponseId->getReponseExpected());
    //     }

    //     $result=0; $y=1;
    //     for($i=0; $i<count($good_answers); $i++){

    //         if ($this->session->get($y) == $good_answers[$i]){
    //             $result++;
    //         }
    //         $y++;
    //     }

    //     $categorieDetails = $this->getDoctrine()->getRepository(Categorie::Class)->findAll()[$categorie-1];
    //     $image = $categorieDetails->getPicture();
    //     $name = $categorieDetails->getName();
        
    //     $note = "$result/10";


    //     $this->session->set("score", $note);
    //     $this->session->set("categorie_id", $categorie);
    //     return $this->render('quiz/score.html.twig', [
    //         'note' => $note,
    //         'categorie' => $name,
    //         'image' => $image
    //     ]);
    // }

    
    
  


}