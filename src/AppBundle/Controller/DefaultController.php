<?php

namespace AppBundle\Controller;


use Monolog\Handler\ChromePHPHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
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
            $wallet = $this->get('app.wallet_manager');
            $params['balances'] = $wallet->getCurrencies($user);
        }

        $cache = $this->get('app.memcached.client');
        $cache->set('mykey', 'foo', 3600);
        $a = $cache->get('mykey');

        return $this->render('default/index.html.twig', $params);
    }

    /**
     * PHPINFO function
     */
    public function phpinfo()
    {
        echo phpinfo();
    }

    /**
     * @return Response
     */
    public function json1()
    {
        $user = $this->getUser();
        $params = [];
        if (isset($user)) {
            $params['username'] = $user->getUsername();
            $wallet = $this->get('app.wallet_manager');
            $params['balances'] = $wallet->getCurrencies($user);
        }

        return new JsonResponse($params);
    }

    /**
     * getenv function
     */
    public function getEnvorinmentVariables()
    {
        echo getenv();
    }
}
