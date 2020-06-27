<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

     /**
     * @Route("/update_profile", name="update_profile")
     */
    public function update(Request $request)
    {
        dump($request);
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $users = $entityManager->getRepository(Users::class)->find($user->getId());
        $users->setUsername($request->get('username'));
        $users->setEmail($request->get('email'));

 
        return $this->redirect("profile");
    }
}
