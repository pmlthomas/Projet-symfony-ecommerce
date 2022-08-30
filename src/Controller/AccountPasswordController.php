<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modification", name="account_password")
     */
    public function index(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $old_password = $form->get('old_password')->getData();
            if($passwordHasher->isPasswordValid($user, $old_password)){
                $raw_new_password = $form->get('new_password')->getData();
                $new_password = $passwordHasher->hashPassword($user, $raw_new_password);

                $user->setPassword($new_password);
                $doctrine = $managerRegistry->getManager();
                $doctrine->persist($user);
                $doctrine->flush();

                $notification = 'Votre mot de passe a bien été mis à jour!';
            }
        }
        return $this->render('account\password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
