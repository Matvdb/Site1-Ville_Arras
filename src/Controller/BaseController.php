<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
        $form = $this->createForm(ContactType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
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
    public function services(Request $request): Response
    {
        $form = $this->createForm(EcoleType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $this->addFlash('notice','Votre demande a bien été prise en compte !');
                return $this->redirectToRoute('ecoles');
            }
        }
        return $this->render('base/ecoles.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/carteID', name: 'carteID')]
    public function carteID(Request $request): Response
    {
        $form = $this->createForm(CarteIDType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $this->addFlash('notice','Votre demande de carte a bien été prise en compte ! Désormais, suivez votre demande.');
                return $this->redirectToRoute('carteID');
            }
        }
        return $this->render('base/carteID.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/avis', name: 'avis')]
    public function avis(Request $request): Response
    {
        $form = $this->createForm(AvisType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $this->addFlash('notice','Votre avis compte beaucoup, merci !');
                return $this->redirectToRoute('avis');
            }
        }
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
    #[Route('/suivi', name: 'suivi')]
    public function suivi(Request $request): Response
    {
        $form = $this->createForm(SuiviIDType::class);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){   
                $this->addFlash('notice','Votre demande de carte a bien été prise en compte ! Désormais, suivez votre demande.');
                return $this->redirectToRoute('suivi');
            }
        }
        return $this->render('base/demandeid.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 
 
 

