<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\TourImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Strava\API\Client;
use Strava\API\Exception;
use Strava\API\OAuth;
use Strava\API\Service\REST;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StravaController extends Controller
{

    /**
     * @Route("/connect/strava/", name="connect_strava")
     */
    public function connect(Request $request)
    {
        $options = [
            'clientId' => getenv('STRAVA_CLIENT_ID'),
            'redirectUri' => $this->generateUrl(
                'check_strava',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];

        $oauth = new OAuth($options);
        return $this->redirect($oauth->getAuthorizationUrl(['scope' => ['view_private']]));
    }

    /**
     * @Route("/check/strava/", name="check_strava")
     */
    public function check(Request $request)
    {
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/refresh/strava", name="refresh_strava")
     */
    public function refresh(Request $request)
    {
        $user = $this->getUser();
        /** @var TourImporter $tour_importer */
        $tour_importer = $this->container->get('App\Service\TourImporter');
        $tour_importer->importForUser($user);

        return $this->redirect($this->generateUrl('homepage'));
    }
}