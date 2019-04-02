<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Course;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


define("PRATICAL",     "pratical");

class UserController extends AbstractController
{
    const PRATICAL  = "pratical";
    const GOODPLAN  = "goodplan";

    /**
    * @Route("/user/space", name="space")
    */
    public function space()
    {

        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        $users = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findAll();

        $info  = array();
        $goodPlan= array();


        foreach ($pages as $key)
        {
            // check if the page is a pratical information or a good plan
            if($key->getCategory()->getLibelle()== self::PRATICAL)
            {
                array_push($info,$pages);

            }else
            {
                array_push($goodPlan,$pages);
            }
        }

        if(!$courses){
            $this->addFlash(
                'notice',
                'Pas de cours présents, ajoutez un nouveau!'
            );
             // throw $this->createNotFoundException('No Courses in the current database');
           return $this->redirectToRoute('addUV');
        }
          

        return $this->render('pages/user/userspace.html.twig',
                                    ['courses' => $courses,
                                    'praticals'=>$info,'goodplans'=>$goodPlan,
                                    'users' => $users
                                    ]);
    }
    
     /**
    * @Route("/user/adduser", name="adduser")
    */
    public function adduser(Request $request)
    {
        // creates a user and gives it some dummy data for this example
        $user = new User();
        //$user->setRoles("ROLE_USER")
        $user->setRoles(array('ROLE_USER','ROLE_ADMIN'));
        $user->setType('member');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getPhoto();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where images of event are stored
            $file->move(
                $this->getParameter('title_directory'),
                $fileName
            );

            $user->setPhoto($fileName);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'notice',
                'Nouvel utilisateur ajouté'
            );

            return $this->redirectToRoute('space');
        }
        return $this->render('pages/user/adduser.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/moduser", name="moduser")
     */
    public function moduser(Request $request)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $file = new File($this->getParameter('get_directory').$user->getPhoto());
        $user->setPhoto($file);

        // creates a user and gives it some dummy data for this example
        //$user->setRoles("ROLE_USER")
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file*/
            $file = $user->getPhoto();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where images of event are stored
            $file->move(
                $this->getParameter('title_directory'),
                $fileName
            );

            $user->setPhoto($fileName);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'notice',
                'Utilisateur modifié!'
            );

            return $this->redirect( '/user/space#utilisateur');
        }
        return $this->render('pages/user/adduser.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/supuser", name="user_delete")
     */

    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));


        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'L\'utilisateur  selectionné a été supprimé!!!'
        );
        return $this->redirectToRoute('space');
    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function add_check_user( Request $request )
    {
         // creates a task and gives it some dummy data for this example
         $user = new User();
         $username = $request->get("username");
         $email = $request->get("email");
         $phone = $request->get("phone");
         $photo = $request->get("photo");
         $username = $request->get("phone");


         var_dump($username,$email,$phone,$photo);
         die();
        return $this->render('pages/user/adduser.html.twig');
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper): Response
    {

        return $this->render('pages/authentification.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }
    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
    
} 