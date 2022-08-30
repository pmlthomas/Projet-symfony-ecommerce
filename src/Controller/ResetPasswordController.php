<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            $id = $user->getId();

            if($user){
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
            }
            $mail = new Mail();
            $url = $this->generateUrl('update_password', [
                'token' => $reset_password->getToken()
            ]);
            $mailContent = "Bonjour".$user->getFirstName()."<br/>Vous avez demandé à réinitialiser votre mot de passe.<br/>";
            $mailContent .= "Cliquez sur le lien suivant pour <a href='".$url."'>changer votre mot de passe</a>";
            $mail->send($user->getEmail(), ucfirst($user->getFirstName()), "Mot de passe oublié", $mailContent);

            return $this->render('reset_password/emailSent.html.twig');
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mon-mot-de-passe", name="update_password")
     */
    public function update()
    {

    }
}