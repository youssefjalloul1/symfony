<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType; // attention au nom !
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class AuthorController extends AbstractController
{
    /* #[Route('author', name: 'app_testcontroller')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestcontrollerController.php',
        ]);
    }*/
    //-------------list affichage----------
    #[Route('/authors', name: 'authors')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors= $authorRepository->findAll();
        return $this->render('author/list.html.twig',['authors'=>$authors,]);
    }
    






 // --------------ajoute manuel-----------
    #[Route('/addauthor', name:'addauthor')]
    public function addauthor(EntityManagerInterface $em): Response{
        $author=new Author();
        $author->setUsername("yassmine");
        $author->setEmail("yass.ggg@gmail.com");
        $em->persist($author);
        $em->flush();

        return new Response("ajout succes");

    }

    //--------------------------ajoute formulaire-----------
    #[Route('/author/new', name:'author-new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $author=new Author();
        $form=$this->createForm(AuthorType::class ,$author);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('authors');

        }
        return $this->render("author/new.html.twig", ['form' => $form->createView()]);
    }
    //---------------modifier author----------------
    #[Route('/author/edit/{id}', name:"author_edit")]
    public function edit(Request $request, Author $author ,EntityManagerInterface $em): Response{
        $form= $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('authors');

        }
        return $this->render('author/edit.html.twig', [
        'form' => $form->createView(),
    ]);

    }   
    //-------------Delete----------------------------------------------
    #[Route('/author/delete/{id}', name:"author_delete")]
    public function delete(Author $author, EntityManagerInterface $em): Response{
        $em->remove($author);
        $em->flush();
        return $this->RedirectToRoute('authors');
    }

}
