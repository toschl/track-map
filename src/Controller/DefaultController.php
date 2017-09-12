<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request)
    {
        $strava_token = getenv('STRAVA_TOKEN');
        $data = [
          'strava_token' => $strava_token,
        ];
        return $this->render('default/index.html.twig', $data);
    }
}