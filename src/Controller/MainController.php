<?php

namespace App\Controller;

use App\Repository\PeakRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param PeakRepository $peakRepository
     * @return Response
     */
    public function index(PeakRepository $peakRepository): Response
    {
        $peaks = $peakRepository->findAll();

        return $this->render('main/index.html.twig', ['peaks' => $peaks]);
    }
}