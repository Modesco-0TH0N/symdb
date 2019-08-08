<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends Controller
{
    /**
     * @Route("/transaction/", name="transaction")
     */
    public function showNewTransactionForm(Request $request)
    {
        return $this->render('./transaction/index.html.twig');
    }
}