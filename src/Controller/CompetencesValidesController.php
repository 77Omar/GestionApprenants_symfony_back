<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetencesValidesController extends AbstractController
{
    /**
     * @Route("/competences/valides", name="competences_valides")
     */
    public function index(): Response
    {
        return $this->render('competences_valides/index.html.twig', [
            'controller_name' => 'CompetencesValidesController',
        ]);
    }
}
