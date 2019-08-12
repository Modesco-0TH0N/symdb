<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $params = [];
        if (isset($user)) {
            $params['username'] = $user->getUsername();
            $wallet = $this->get('app.domain.wallet');
            $params['balances'] = $wallet->getCurrencies($user);
        }
        return $this->render('default/index.html.twig', $params);
    }
}
