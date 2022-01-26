<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;


class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     * 
     */
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/users/add", methods={"GET","POST"}, name="user")
     * 
     */
    public function Adduser(): Response
    {
        
        return $this->render('user/add_user.html.twig') ;
    }




    /**
     * @Route("/users/save", methods={"POST"}, name="save_user")
     */
    public function saveUser(Request $request, ManagerRegistry $doctrine):Response{

        $user = new User();
        $user -> setFirstName($request->request->get('first_name'));
        $user -> setLastName($request->request->get('last_name'));
        $user -> setEmail($request->request->get('email'));
        
        $entityManager = $doctrine->getManager();

        $entityManager->persist($user);
        
        $entityManager->flush();
        
       

        

        return $this->redirectToRoute('users');
    }

  

    /**
     * @Route("/users/edit/{id}", methods={"GET", "POST"}, name="user_edit")
     */
    public function editUser(User $user )
    {
        $message = null;
        
        return $this->render("user/edit_user.html.twig", [
            'message' => $message,
            'user_id' => $user->getId(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="delete_user")
     */

     public function deleteUser(User $user, ManagerRegistry $doctrine):Response{


        $users = $doctrine->getManager();
        $users->remove($user);        
        $users->flush();

        return $this->redirectToRoute('users');
     }

     /**
     * @Route("/users/update/{id}", methods={"POST"}, name="update_user")
     */
    public function updateUser(Request $request, ManagerRegistry $doctrine, User $user):Response
    {   
        $user -> setFirstName($request->request->get('first_name'));
        $user -> setLastName($request->request->get('last_name'));
        $user -> setEmail($request->request->get('email'));
        
        $entityManager = $doctrine->getManager();

        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('users');
    }

    /**
     * @Route("/users/{id}/projects", methods={"GET"}, name="project_user")
     */
    public function projectUser(User $user)


    {
        $projects = $user->getProjects();
        return $this->render('user/project_user.html.twig',[
            'projects' => $projects,
            'user' => $user,
        ]) ;
    }
}
