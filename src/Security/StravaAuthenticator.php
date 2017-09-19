<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Strava\API\OAuth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class StravaAuthenticator extends AbstractGuardAuthenticator
{

    protected $em;

    protected $router;

    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function start(
        Request $request,
        AuthenticationException $authException = null
    ) {
        return $this->redirect($this->generateUrl('connect_strava'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \League\OAuth2\Client\Token\AccessToken|void
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/check/strava/') {
            return;
        }

        $options = [
          'clientId' => getenv('STRAVA_CLIENT_ID'),
          'clientSecret' => getenv('STRAVA_CLIENT_SECRET'),
        ];

        $oauth = new OAuth($options);
        $token = $oauth->getAccessToken('authorization_code', [
          'code' => $request->get('code')
        ]);

        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $values = $credentials->getValues();

        $existingUser = $this->em->getRepository(User::class)->findOneBy([
            'strava_username' => $values['athlete']['username']
        ]);
        if ($existingUser) {
            $existingUser->strava_access_token = $credentials->getToken();
            $this->em->persist($existingUser);
            $this->em->flush();
            return $existingUser;
        }

        $user = new User();
        $user->id = null;
        $user->name = $values['athlete']['username'];
        $user->strava_id = $values['athlete']['id'];
        $user->strava_access_token = $credentials->getToken();
        $user->strava_username = $values['athlete']['username'];

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        return new RedirectResponse($this->router->generate('homepage'));
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) {
        return new RedirectResponse($this->router->generate('homepage'));
    }
}
