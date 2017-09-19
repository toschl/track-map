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
        $google_maps_api_key = getenv('GOOGLE_MAPS_API_KEY');
        $data = [
          'google_maps_api_key' => $google_maps_api_key,
        ];
        return $this->render('default/index.html.twig', $data);
    }
}