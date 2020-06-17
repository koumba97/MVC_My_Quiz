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
                <p>Tu es sur le point de changer de catégorie. Si tu changes de catégorie, ton avancé sur le quiz <span><?php echo $categorieName; ?></span> sera perdu</p>
                <div class='buttons'>
                    <div class='button'>Finir ma partie</div> 
                    <div class='button'>Abandonner</div>
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
            return $this->redirect('../../quiz/score');
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
     * @Route("/quiz/score", name="score")
     */
    public function score(Request $request){
        return $this->render('quiz/score.html.twig');
    }

}


if (isset($_POST['change'])){
    ?>
    <style>
        .popup{
            display:block;
        }
    </style>
    <?php

}
else{
    ?>
    <style>
        .popup{
            display:none;
        }
    </style>
    <?php

}