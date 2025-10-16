<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/add', name: 'book_add')]
    public function addBook(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setPublished(true); // Initialisation automatique à true

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1); // Incrémentation

            $em->persist($book);
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès !');

            return $this->redirectToRoute('app_book'); // Retour à la liste ou page souhaitée
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
