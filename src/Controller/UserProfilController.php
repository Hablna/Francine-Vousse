<?php

namespace App\Controller;

use App\Form\ProfleType;
use App\Form\RegistrationFormType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_account')]
    public function edit(Request $request,
                         EntityManagerInterface $em,
                         SluggerInterface $slugger,
                         OffreRepository $offreRepository
    ): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ProfleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $file = $form->get('profilePicture')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where profile pictures are stored
                try {
                    $file->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch ( FileException $e) {
                    // Handle exception if something happens during file upload
                    $this->addFlash('error', 'erreur lors de l\'upload de la photo de profil');
                }

                // Update the 'profilePicture' property to store the file name
                // instead of its contents
                $user->setProfilePicture($newFilename);
            }

            // Save the user entity
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_account');
        }
        //historique des offres
        $offrePassees = $offreRepository->findPastOffers($user->getId());
        $offreFutures = $offreRepository->findFutureOffers($user->getId());

        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
            'offresPassees' => $offrePassees,
            'offresFutures' => $offreFutures,
        ]);
    }
}
