<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('app.html.twig');
    }
            /**
     * @Route("/generate-password", name="generat_password", methods="GET")
     */
    public function generatePassword(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
	    $user = $this->getDoctrine()->getRepository(User::class)->find(1);
            $newPwdEncoded = $passwordEncoder->encodePassword($user, '123456');
	    dump($newPwdEncoded);exit;
    }
}
