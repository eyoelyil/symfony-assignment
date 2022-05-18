<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CRUDController extends AbstractController
{
    #[Route('/crud/list', name: 'crud')]
    public function index(EntityManagerInterface $em): Response
    {   
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->render('crud/index.html.twig',  ['tasks'=>$tasks]);
    }

    // #[Route('/create', name: 'create_task', methods: ['POST'])]
    // public function create(Request $request, ManagerRegistry $doctrine): Response
    // {
    //     $title = trim($request->get('title'));
    //     if(!empty($title)){
    //         $entityManager = $doctrine->getManager();
    //         $task = new Task();
    //         $task->setTitle($title);
    //         $entityManager->persist($task); // preparing for insert
    //         $entityManager->flush(); // executing insert
    //         return new Response('Task created successfully');
    //     } else {
    //        return $this->redirectToRoute('crud');
    //     }
        
    // }

    #[Route('/create', name: 'create_task', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {


        $title = trim($request->get("title"));
        $id = trim($request->get("id"));
        // if (!empty($id)){
        if ($id){
            $entityManager = $doctrine->getManager();
            $task =$entityManager->getRepository(Task::class)->find($id);
            $task->setTitle($title);
            $entityManager->flush();
            return $this->redirectToRoute('crud');
        }elseif(!empty($title)){

        // print_r($title.$id);
        // if (!empty($title)){

        $entityManager = $doctrine->getManager();
        $task = new Task();
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

  

        return $this->redirectToRoute('crud');

        }

    }

   
    #[Route('/update/{id}', name: 'update_task')]
    public function update($id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('crud');
        
    }

    #[Route('/delete/{id}', name: 'delete_task')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);

        $entityManager->flush();
        return $this->redirectToRoute('crud');
    }
  
//     #[Route('/project', name: 'project_index', methods: ['GET'])]
}
