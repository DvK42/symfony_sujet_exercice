<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  #[Route(path: '/connexion', name: 'app_login')]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  #[Route(path: '/logout', name: 'app_logout')]
  public function logout(): void
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }

  #[Route('/inscription', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
  {
    if ($this->getUser()) {
      return $this->redirectToRoute('app_home');
    }

    $error = null;

    $user = new User();
    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setRoles(['ROLE_USER']);

      $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
      $user->setPassword($hashedPassword);

      $entityManager->persist($user);
      $entityManager->flush();

      $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');

      return $this->redirectToRoute('app_login');
    }

    return $this->render('security/register.html.twig', ['error' => $error, 'registrationForm' => $form->createView(),]);
  }

  #[Route('/mot-de-passe-oublie', name: 'app_forgot_password')]
  public function forgotPassword(
    Request $request,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer
  ): Response {
    $form = $this->createForm(ForgotPasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $email = $form->get('email')->getData();

      // Trouver l'utilisateur par email
      $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

      if ($user) {
        // Générer un token de réinitialisation
        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $entityManager->flush();

        // Envoyer l'email de réinitialisation
        $resetUrl = $this->generateUrl('app_reset_password', [
          'token' => $resetToken,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
          ->from('no-reply@example.com')
          ->to($user->getEmail())
          ->subject('Réinitialisation de votre mot de passe')
          ->html("<p>Bonjour,</p><p>Pour réinitialiser votre mot de passe, cliquez sur le lien suivant : <a href=\"$resetUrl\">Réinitialiser mon mot de passe</a></p>");

        try {
          $mailer->send($email);
        } catch (\Exception $e) {
          return new Response('Erreur lors de l\'envoi : ' . $e->getMessage());
        }
        $this->addFlash('success', 'Un email de réinitialisation a été envoyé si l’adresse email existe.');

        return $this->redirectToRoute('app_login');
      } else {
        return $this->redirectToRoute('app_home');

      }

      $this->addFlash('success', 'Un email de réinitialisation a été envoyé si l’adresse email existe.');
    }

    return $this->render('security/forgot.html.twig', [
      'forgotPasswordForm' => $form->createView(),
    ]);
  }

  #[Route('/reset-password/{token}', name: 'app_reset_password')]
  public function resetPassword(
    string $token,
    Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
  ): Response {
    // Trouver l'utilisateur avec ce token
    $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);

    if (!$user) {
      $this->addFlash('error', 'Lien de réinitialisation invalide ou expiré.');
      return $this->redirectToRoute('app_forgot_password');
    }

    $form = $this->createForm(ResetPasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $newPassword = $form->get('password')->getData();
      $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
      $user->setPassword($hashedPassword);
      $user->setResetToken(null); // Supprimer le token après usage
      $entityManager->flush();

      $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

      return $this->redirectToRoute('app_login');
    }

    return $this->render('security/reset.html.twig', [
      'resetPasswordForm' => $form->createView(),
      'username' => $user->getFirstName(),
    ]);
  }


  #[Route('/access-denied', name: 'app_access_denied')]
  public function accessDenied(): Response
  {
    return $this->render('security/access_denied.html.twig', [
      'message' => 'Votre compte a été bloqué. Veuillez contacter l\'administration pour plus d\'informations.',
    ]);
  }
}
