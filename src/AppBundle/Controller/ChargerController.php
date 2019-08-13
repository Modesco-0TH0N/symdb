<?php


namespace AppBundle\Controller;


use AppBundle\Utils\Utils;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Ticker;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ChargerController
 * @package AppBundle\Controller
 */
class ChargerController extends Controller
{
    /**
     * @param Request $request
     * @param String $tick
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function charge(Request $request, String $tick = '')
    {
        $user    = $this->getUser();
        $ticker  = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy(['name' => $tick]);
        $payment = new Payment($user, $ticker);

        $form = $this->createFormBuilder($payment)
            ->add('amount', TextType::class, ['label' => 'Amount:'])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);
        $validator = $this->get('validator');
        $payment   = $form->getData();
        $errors    = Utils::getErrors($validator->validate($payment));

        //
        if ($form->isSubmitted() && $form->isValid() && (count($errors) === 0)) {
            $wallet = $this->get('app.wallet_manager');
            $wallet->charge($user, $payment);
            return $this->redirectToRoute('homepage');
        }
        //

        return $this->render('charger/index.html.twig', [
            'tick'   => $tick,
            'form'   => $form->createView(),
            'errors' => $errors
        ]);
    }
}