<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $params = [];
        if (isset($user)) {
            $params['username'] = $user->getUsername();
            $entityManager = $this->getDoctrine()->getManager();
            $wallet = new Wallet($user, $entityManager);
            $params['balances'] = $wallet->getCurrencies();
        }
        return $this->render('./default/index.html.twig', $params);
    }
}
