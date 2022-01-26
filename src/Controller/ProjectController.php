<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use App\Entity\User;
use \Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;



class ProjectController extends AbstractController
{
   /**
     * @Route("/projects", name="projects")
     * 
     */
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();
        return $this->render('project/project_list.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/projects/add", methods={"GET"}, name="add_project")
     * 
     */
    public function Addproject(): Response
    {
        
        return $this->render('project/add_project.html.twig') ;
    }




    /**
     * @Route("/projects/add/save", methods={"POST"}, name="save_project")
     */
    public function saveProject(Request $request, ManagerRegistry $doctrine,  ValidatorInterface $validator ):Response{

        
        $format = 'Y-m-d';

        $project = new Project();
        $project -> setName($request->request->get('name'));
        $project -> setDescription($request->request->get('description'));
      
        $project->setStartDateStr($request->request->get('start_date'));
        $project->setEndDateStr($request->request->get('end_date'));

      

        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            
            $this-> addFlash('error',$errors);
            return $this-> redirectToRoute('add_project');
        }

        $project->setStartDate(\DateTime::createFromFormat($format, $request->request->get('start_date')));
        $project->setEndDate(\DateTime::createFromFormat($format, $request->request->get('end_date')));
        
        $entityManager = $doctrine->getManager();

        $entityManager->persist($project);
        
        $entityManager->flush();
        
       

        

        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/projects/edit/{id}", methods={"GET", "POST"}, name="project_edit")
     */
    public function editProject(Project $project )
    {
        $users = $project->getUsers();
        $tasks = $project->getTasks();
        
        return $this->render("project/edit_project.html.twig", [
            
            'tasks' => $tasks,
            'users' => $users,
            'project' => $project,
        ]);
    }

    /**
     * @Route("/projects/update/{id}", methods={"POST"}, name="update_project")
     */
    public function updateProject(Request $request, ManagerRegistry $doctrine, Project $project):Response{

        $entityManager = $doctrine->getManager();

       
        
        $project -> setName($request->request->get('name'));
        $project -> setDescription($request->request->get('description'));
        $project->setStartDate(new \DateTime($request->request->get('start_date')));
        $project->setEndDate(new \DateTime($request->request->get('end_date')));
      
        
      
      
        
       
        
        $entityManager->persist($project);
        $entityManager->flush();
        
       

        

        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/projects/delete/{id}", name="delete_project")
     */

    public function deleteProject(Project $project, ManagerRegistry $doctrine):Response{


        $projects = $doctrine->getManager();
        $projects->remove($project);        
        $projects->flush();

        return $this->redirectToRoute('projects');
     }

     /**
      * @Route("/projects/{id}/user", methods={"GET"}, name="add_user_project")
      */

      public function addUserProject(UserRepository $userRepository, Project $project): Response
      {
          $users = $userRepository->findAll();
          $projectUsers = $project->getUsers();
          
          return $this->render('project/add_user.html.twig', [
              'users' => $users,
              'project' => $project,
              'projectUsers' => $projectUsers
          ]);
      }

    /**
     * @Route("/projects/{id}/user/save", methods={"POST"}, name="projects_addUser_save")
     */
    public function projectsAddUserSave(LoggerInterface $logger, ManagerRegistry $doctrine, UserRepository $userRepository, Request $request, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $project->clearUsers();

        $listIdUser = $request->request->get('user_id', []);

        $logger->debug("valeur userId", ["userId"=>$listIdUser]);
        
        foreach($listIdUser as $userId) {
            $user = $userRepository->find($userId);
            $project->addUser($user);
        }

        $entityManager->persist($project);
        $entityManager->flush();

        return $this->redirectToRoute('projects');
    }

}
