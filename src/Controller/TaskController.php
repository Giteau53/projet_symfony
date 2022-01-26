<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Task;
use App\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TaskController extends AbstractController
{
     /**
     * @Route("project/{id}/tasks", name="tasks")
     * 
     */
    public function tasks(Project $project): Response
    {
        $tasks = $project->getTasks();
        return $this->render('task/task_list.html.twig', [
            'tasks' => $tasks,
            'project' => $project
        ]);
    }

    /**
     * @Route("/project/{id}/tasks/add", methods={"GET"}, name="add_task")
     * 
     */
    public function Addtask(Project $project): Response
    {
        $users = $project->getUsers();

        return $this->render('task/add_task.html.twig', [
            'project' => $project,
            'users' => $users,
        ]);
    }


    /**
     * @Route("/project/{id}/tasks/add/save", methods={"POST"}, name="save_task")
     */
    public function tasksAddSave(ManagerRegistry $doctrine, Request $request, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $task = new Task();

        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
        $task->setStartDate(new \DateTime($request->request->get('start_date')));
        $task->setEndDate(new \DateTime($request->request->get('end_date')));
        $task->setProject($project);
        
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks', ["id" => $project->getId()]);
    }


    // /**
    //  * @Route("/project/{id}/tasks/add/save", methods={"POST"}, name="save_task")
    //  */
    // public function saveTask(Request $request, ManagerRegistry $doctrine, Project $project, ValidatorInterface $validator, Task $task):Response{

    //     $entityManager = $doctrine->getManager();

    //     $format = 'Y-m-d';

    //     $task = new Task();
    //     $task -> setName($request->request->get('name'));
    //     $task -> setDescription($request->request->get('description'));
    //     $task->setStartDateStr($request->request->get('start_date'));
    //     $task->setEndDateStr($request->request->get('end_date'));
    //     $task->setProject($project);

    //     $errors = $validator->validate($task);
    //     if (count($errors) > 0) {
            
    //         $this-> addFlash('error',$errors);
    //         return $this-> redirectToRoute('add_task');
    //     }

    //     $task->setStartDate(\DateTime::createFromFormat($format, $request->request->get('start_date')));
    //     $task->setEndDate(\DateTime::createFromFormat($format, $request->request->get('end_date')));

    //     $entityManager->persist($task);
        
    //     $entityManager->flush();
        
       

        

    //     return $this->redirectToRoute('tasks', ["id" => $project->getId()]);
    // }

   /**
     * @Route("/project/{project_id}/tasks/{task_id}/edit", name="tasks_edit")
     * @ParamConverter("project", options={"mapping": {"project_id": "id"}})
     * @ParamConverter("task", options={"mapping": {"task_id": "id"}})
     */
    public function tasksEdit(Project $project, Task $task): Response
    {
        return $this->render('task/edit_task.html.twig', [
            'project' => $project,
            'task' => $task,
        ]);
    }

    /**
     * @Route("/project/{project_id}/tasks/{task_id}/edit/save", methods={"POST"}, name="task_edit_save")
     * @ParamConverter("project", options={"mapping": {"project_id": "id"}})
     * @ParamConverter("task", options={"mapping": {"task_id": "id"}})
     */
    public function taskEditSave(ManagerRegistry $doctrine, Request $request, Task $task, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
        $task->setStartDate(new \DateTime($request->request->get('start_date')));
        $task->setEndDate(new \DateTime($request->request->get('end_date')));

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks', ["id" => $project->getId()]);
    }

    
    /**
     * @Route("/project/{project_id}/tasks/{task_id}/delete", methods={"GET"}, name="task_delete")
     * @ParamConverter("project", options={"mapping": {"project_id": "id"}})
     * @ParamConverter("task", options={"mapping": {"task_id": "id"}})
     */
    public function taskDelete(ManagerRegistry $doctrine, Task $task, Project $project): Response
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks', ["id" => $project->getId()]);
    }

}
