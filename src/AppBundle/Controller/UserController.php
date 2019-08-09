<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\User;
use Symfony\Component\Validator\ConstraintViolation;

class UserController extends Controller
{
    /**
     * @Route("/user/", name="user")
     */
    public function showUserIndex(Request $request)
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/user/new", name="new_user_form")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
        $errors = $this->getErrors($validator->validate($user));
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

    private function encodePassword($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    /**
     * @Route("/user/success/", name="user_registered_successful")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserRegisteredSuccessful(Request $request)
    {
        return $this->render('user/success.html.twig');
    }
}