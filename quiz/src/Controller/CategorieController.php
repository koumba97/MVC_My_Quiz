<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategorieController extends AbstractController
{

    /**
     * @var CategorieRepository
     */
    private $repository;
    private $session;

    public function __construct(CategorieRepository $repository, SessionInterface $session){
        $this->repository = $repository;
        $this->session = $session;
    }

    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(Request $request) : Response
    {
        $categorieName = $this->getDoctrine()->getRepository(Categorie::Class)->findAll();
        
        
        if($request->get('playername')!== NULL){
            $this->session->set('playerName', $request->get('playername'));
        }
        
        $arrayCategorie=array();
        for($i=0; $i< count($categorieName); $i++){
            array_push($arrayCategorie, [$categorieName[$i]->getName(), $categorieName[$i]->getId(), $categorieName[$i]->getPicture()]);
        }

        $this->session->remove("categorie");
        for($i=1; $i<=10; $i++){
            $this->session->remove($i);
        }
        $this->session->remove("categorie");
        
        dump($this->session->all());
        

        return $this->render('categorie/categories.html.twig', [
            'categories' => $arrayCategorie,
            
        ]);
        
    }
}