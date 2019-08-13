<?php


namespace AppBundle\Controller;


use AppBundle\Utils\Utils;
use AppBundle\Entity\Ticker;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\Balance;
use AppBundle\Entity\Rate;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ExchangerController
 * @package AppBundle\Controller
 */
class ExchangerController extends Controller
{
    /**
     * @param Request $request
     * @param String $tick
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function change(Request $request, $tick = '')
    {
        $user    = $this->getUser();
        $ticker  = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy(['name' => $tick]);
        $tickers = $this->getDoctrine()->getRepository(Ticker::class)->findAll();
        $tickers = $this->remakeTickers($tickers, $tick);

        $transaction = new Transaction($user, $ticker);

        $form = $this->createFormBuilder($transaction)
            ->add('amount1', TextType::class, ['label' => 'Amount:'])
            ->add('ticker2', ChoiceType::class, ['choices' => $tickers])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);
        $validator   = $this->get('validator');
        /** @var Transaction $transaction */
        $transaction = $form->getData();
        $ticker2     = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy([
            'name' => $transaction->getTicker2(),
        ]);
        $transaction->setTicker2($ticker2);
        $errors = Utils::getErrors($validator->validate($transaction));

        if ($form->isSubmitted() && $form->isValid() && (count($errors) === 0)) {
            $exchanger = $this->get('app.exchanger_manager');
            $exchanger->change($user, $transaction);
            return $this->redirectToRoute('homepage');
        }

        $balance = $this->getDoctrine()->getRepository(Balance::class)->findOneBy([
            'user' => $user,
            'ticker' => $ticker
        ]);
        $balance = $balance ? $balance : new Balance($user, $ticker);
        $tickerBalance = $balance->getAmount();
        $rates = $this->getDoctrine()->getRepository(Rate::class)->findBy(['ticker1' => $ticker]);
        $rates = array_map(function ($rate) {
            /** @var Rate $rate */
            return [
                'ticker1' => $rate->getTicker1()->getName(),
                'ticker2' => $rate->getTicker2()->getName(),
                'price' => $rate->getPrice()
            ];
        }, $rates);

        return $this->render('exchanger/index.html.twig', [
            'tick' => $tick,
            'form' => $form->createView(),
            'errors' => $errors,
            'tickerBalance' => $tickerBalance,
            'rates' => $rates
        ]);
    }

    /**
     * @param $tickers
     * @param $tick
     * @return array|null
     */
    private function remakeTickers($tickers, $tick)
    {
        $tickers = array_flip(array_map(function($ticker) {
            /** @var Ticker $ticker */
            return $ticker->getName();
        }, $tickers));
        foreach ($tickers as $key => $value) {
            $tickers[$key] = $key;
        }
        unset($tickers[$tick]);
        return $tickers;
    }
}