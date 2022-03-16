<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Contact;
use App\Form\AvisType;
use App\Form\EcoleType;
use App\Form\CarteIDType;


class BaseController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
            
        ]);
    }


    
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('reply@nuage-pedagogique.fr')
                ->subject($contact->getSujet())
                ->htmlTemplate('emails/email.html.twig')
                ->context([
                    'nom'=> $contact->getNom(),
                    'sujet'=> $contact->getSujet(),
                    'message'=> $contact->getMessage()
                ]);
                $contact->setDateEnvoi(new \Datetime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
              
                $mailer->send($email);
                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/presentation', name: 'presentation')]
    public function presentation(): Response
    {
        return $this->render('base/presentation.html.twig', [
            
        ]);
    }

    #[Route('/ecoles', name: 'ecoles')]
    public function services(): Response
    {
        $form = $this->createForm(EcoleType::class);
        return $this->render('base/ecoles.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/carteID', name: 'carteID')]
    public function carteID(): Response
    {
        $form = $this->createForm(CarteIDType::class);
        return $this->render('base/carteID.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/avis', name: 'avis')]
    public function avis(): Response
    {
        $form = $this->createForm(AvisType::class);
        return $this->render('base/avis.html.twig', [ // étape 3
            'form' => $form->createView()
        ]);
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('notice', 'Adresse vérifiée');

        return $this->redirectToRoute('app_login');
    }
} 
 
 

