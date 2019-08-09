<?php


namespace AppBundle\Controller;

use AppBundle\Domain\Exchanger;
use AppBundle\Domain\Wallet;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Ticker;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use AppBundle\Entity\Balance;
use AppBundle\Entity\Rate;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExchangerController extends Controller
{
    /**
     * @Route("/change/{tick}", name="change")
     * @param Request $request
     * @param null $tick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function change(Request $request, $tick = null)
    {
        $user = $this->getUser();
        $ticker = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy(['name' => $tick]);
        $tickers = $this->getDoctrine()->getRepository(Ticker::class)->findAll();
        $tickers = $this->remakeTickers($tickers, $tick);

        $transaction = new Transaction($user, $ticker);

        $form = $this->createFormBuilder($transaction)
            ->add('amount1', TextType::class, ['label' => 'Amount:'])
            ->add('ticker2', ChoiceType::class, ['choices' => $tickers])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);
        $validator = $this->get('validator');
        $transaction = $form->getData();
        $ticker2 = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy([
            'name' => $transaction->getTicker2(),
        ]);
        $transaction->setTicker2($ticker2);
        $errors = $this->getErrors($validator->validate($transaction));

        if ($form->isSubmitted() && $form->isValid() && (count($errors) === 0)) {
            $entityManager = $this->getDoctrine()->getManager();
            $exchanger = new Exchanger($user, $entityManager);
            $transaction = $exchanger->change($transaction);
            $entityManager->persist($transaction);
            $entityManager->flush();
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
            return [
                'ticker1' => $rate->getTicker1()->getName(),
                'ticker2' => $rate->getTicker2()->getName(),
                'price' => $rate->getPrice()
            ];
        }, $rates);

        return $this->render('./exchanger/index.html.twig', [
            'tick' => $tick,
            'form' => $form->createView(),
            'errors' => $errors,
            'tickerBalance' => $tickerBalance,
            'rates' => $rates
        ]);
    }

    /**
     * @param $errors
     * @return array
     */
    private function getErrors($errors)
    {
        $err = [];

        foreach ($errors as $error) {
            if (!isset($err[$error->getPropertyPath()])) {
                $err[$error->getPropertyPath()] = [];
            }
            $err[$error->getPropertyPath()][] = $error;
        }

        return $err;
    }

    private function remakeTickers($tickers, $tick)
    {
        $tickers = array_flip(array_map(function($ticker) {
            return $ticker->getName();
        }, $tickers));
        foreach ($tickers as $key => $value) {
            $tickers[$key] = $key;
        }
        unset($tickers[$tick]);
        return $tickers;
    }
}