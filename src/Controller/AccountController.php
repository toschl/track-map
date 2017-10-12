<?php

namespace App\Controller;

use App\Entity\Tour;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{

    /**
     * @Route("/account/delete", name="account_delete")
     */
    public function delete(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createFormBuilder()
            ->add('submit', SubmitType::class, ['label' => 'Delete account'])
            ->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            $this->addFlash('success', 'Your account has been deleted successfully.');
            return $this->redirect($this->generateUrl('homepage'));
        }

        $google_maps_api_key = getenv('GOOGLE_MAPS_API_KEY');

        return $this->render('account/delete.html.twig', [
            'google_maps_api_key' => $google_maps_api_key,
            'form' => $form->createView(),
        ]);
    }
}
