<?php

namespace App\Controller;

use App\Entity\User;
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
        try {
            $options = [
              'clientId' => getenv('STRAVA_CLIENT_ID'),
              'clientSecret' => getenv('STRAVA_CLIENT_SECRET'),
            ];

            $oauth = new OAuth($options);
            $token = $oauth->getAccessToken('authorization_code', [
              'code' => $request->get('code')
            ]);

            $values = $token->getValues();
            $user = new User;
            $user->id = 1;
            $user->name = $values['athlete']['username'];
            $user->strava_id = $values['athlete']['id'];
            $user->strava_access_token = $token->getToken();
            $user->strava_username = $values['athlete']['username'];

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        } catch (Exception $e) {
            print $e->getMessage();
            return $this->redirect($this->generateUrl('homepage'));
        }
    }
}