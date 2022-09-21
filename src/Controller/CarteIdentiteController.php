<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\AjoutCarteIDType;
use App\Entity\Identite;
use App\Form\SuiviIDType;

class CarteIdentiteController extends AbstractController
{
    #[Route('/demande_carte', name: 'carte_identite')]
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SuiviIDType::class);

        if($request->isMethod('POST')){
        
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $this->addFlash('notice','Votre demande est suivi');
                return $this->redirectToRoute('carte_identite');
            }
        }

        return $this->render('base/demandeid.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

