<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }


    /**
     * @Route("/user", name="user")
     */
    public function index(){
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

     /**
     * @Route("/deconnection", name="deconnection")
     */
    public function deconnection(Request $request){
        $this->session->clear();
        $request->getSession()->invalidate(1);
        return $this->redirect('/');
    }
}
