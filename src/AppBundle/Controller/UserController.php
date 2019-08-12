<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;
use AppBundle\Utils\Utils;

class UserController extends Controller
{
    /**
     * @Route("/user/", name="user")
     */
    public function showUserIndex()
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/user/new", name="new_user_form")
     * @param Request $request
     * @return RedirectResponseAlias|Response
     */
    public function showNewUserForm(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => 'User Login:'])
            ->add('plainPassword', PasswordType::class)
            ->add('save', SubmitType::class, ['label' => 'Register'])
            ->getForm();

        $form->handleRequest($request);
        $validator = $this->get('validator');
        $user = $form->getData();
        $errors = Utils::getErrors($validator->validate($user));
        $user->setRole('user')->setPassword($this->encodePassword($user->getPlainPassword()))->eraseCredentials();

        if ($form->isSubmitted() && $form->isValid() && (count($errors) === 0)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_registered_successful');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    private function encodePassword($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    /**
     * @Route("/user/success/", name="user_registered_successful")
     * @param Request $request
     * @return Response
     */
    public function showUserRegisteredSuccessful(Request $request)
    {
        return $this->render('user/success.html.twig');
    }
}