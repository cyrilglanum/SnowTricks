<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Entity\Users;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("/reset-password/app_forgot_password_request",name="app_forgot_password_request")
     */

    public function request(Request $request, MailerInterface $mailer, UsersRepository $users): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not

        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_ '), '=');
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findOneBy(['tokenResetPassword' => $token]);

            if ($user) {
                $userEmail = $user->getEmail();
                $request->request->set("userEmail", $userEmail);
            }

            $form = $this->createForm(ResetPasswordType::class, $request);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
                    'tokenResetPassword' => $form->getData()->request->get('reset_password')['token']
                ]);

                $token_form = $form->getData()->attributes->get('token');

                if ($token_form === $user->getTokenResetPassword()) {
                    $user->setPassword(
                        $userPasswordHasherInterface->hashPassword(
                            $user,
                            $form->getData()->request->get('reset_password')['newPassword']
                        )
                    );

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Le mot de passe a bien ??t?? modifi??.');

                    return $this->redirectToRoute('app_home');
                } else {
                    $this->addFlash('error', 'Une erreur a eu lieu lors de la modification, veuillez r??essayer.');
                    $this->redirectToRoute('app_home');
                }
            }

            return $this->render('reset_password/reset2.html.twig', array(
                'form' => $form->createView(),
                'token' => $token,
            ));
        }
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset-password/{token}", name="app_reset_password_with_token")
     */
    public function resetPassword(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            $reset_password = new Users();
            $form = $this->createForm(ResetPasswordType::class, $reset_password);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                return $this->redirectToRoute('app_home');
            }
            return $this->redirectToRoute('app_reset_password_with_token');
        }
    }


    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        $resetToken = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_ '), '=');

        $user->setTokenResetPassword($resetToken);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('cyrilglanum@ocprojects.fr', 'SnowTricks Mail Manager'))
            ->to($user->getEmail())
            ->subject('R??initialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
//        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
