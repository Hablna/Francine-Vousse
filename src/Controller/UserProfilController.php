<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_account')]
    public function index(): Response
    {
        //si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //si l'utilisateur est connecté, on affiche sa page de profil
        $user = $this->getUser();
        //on recupere toute les informations de l'utilisateur
        $userEmail = $user->getEmail();
        $userName = $user->getName();
        $userUsername = $user->getUsername();
        $userPrenom = $user->getPrenom();
        $userCivility = $user->getCivility();

        return $this->render('user_profil/index.html.twig', [
            'name' => $userName,
            'email' => $userEmail,
            'username' => $userUsername,
            'prenom' => $userPrenom,
            'civility' => $userCivility,
            'firstname' => $user->getPrenom(),
        ]);
    }
}
