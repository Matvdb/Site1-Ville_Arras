<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    #[Route('/liste-contact', name: 'list-contact')]
    public function listeContact(): Response
    {
        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repoContact->findAll();
        return $this->render('base/list-contact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    
} 
 
 

