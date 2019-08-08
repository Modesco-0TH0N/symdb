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
        $userProperties = [];
        if (isset($user)) {
            $userProperties['username'] = $user->getUsername();
            $entityManager = $this->getDoctrine()->getManager();
            $wallet = new Wallet($user, $entityManager);
            $userProperties['balances'] = [];
            $currencies = $wallet->getCurrencies();
            foreach ($currencies as $currency) {
                $ticker = $currency->getTicker()->getDescription();
                $userProperties['balances'][$ticker] = $currency->getAmount();
            }
        }
        $params = array_merge($userProperties, [], []);
        return $this->render('./default/index.html.twig', $params);
    }
}
