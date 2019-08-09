<?php


namespace AppBundle\Controller;

use AppBundle\Domain\Wallet;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Ticker;
use AppBundle\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChargerController extends Controller
{
    /**
     * @Route("/charge/{tick}", name="charge")
     * @param Request $request
     * @param null $tick
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function charge(Request $request, $tick = null)
    {
        $user = $this->getUser();
        $ticker = $this->getDoctrine()->getRepository(Ticker::class)->findOneBy(['name' => $tick]);
        $payment = new Payment($user, $ticker);

        $form = $this->createFormBuilder($payment)
            ->add('amount', TextType::class, ['label' => 'Amount:'])
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);
        $validator = $this->get('validator');
        $payment = $form->getData();
        $errors = $this->getErrors($validator->validate($payment));

        if ($form->isSubmitted() && $form->isValid() && (count($errors) === 0)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();
            $wallet = new Wallet($user, $entityManager);
            $wallet->charge($payment);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('./charger/index.html.twig', [
            'tick' => $tick,
            'form' => $form->createView(),
            'errors' => $errors
        ]);

        return $this->render('./charger/index.html.twig', ["tick" => $tick]);
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
}