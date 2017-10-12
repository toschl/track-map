<?php

namespace App\Controller;

use App\Entity\Tour;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        $user = $this->getUser();
        if ($user) {
            $google_maps_api_key = getenv('GOOGLE_MAPS_API_KEY');
            $data = [
              'google_maps_api_key' => $google_maps_api_key,
            ];
            return $this->render('default/map.html.twig', $data);
        } else {
            return $this->render('default/welcome.html.twig', []);
        }
    }

    /**
     * @Route("/data", name="data")
     */
    public function data(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse([]);
        }
        $tourRepository = $this->getDoctrine()->getManager()->getRepository(Tour::class);
        $entites = $tourRepository->findBy(['user' => $user->getId()]);
        $data = [];
        foreach ($entites as $entity) {
            $data[] = [
                'name' => $entity->name,
                'sport' => $entity->sport->name,
                'distance' => $entity->distance,
                'start_date' => $entity->start_date->format('Y-m-d'),
                'summary_polyline' => $entity->map_polyline,
            ];
        }
        return new JsonResponse($data);
    }
}
