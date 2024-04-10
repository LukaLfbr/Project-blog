<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Form\PostsType;
use App\Repository\BlogPostsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

class PostsController extends AbstractController
{
    // Read 📖📖📖📖📖📖📖
    #[Route('/posts', name: 'app.posts')]
    public function showPosts(BlogPostsRepository $repository): Response
    {
        $blogPosts = $repository->findAll();

        $user = $this->getUser();

        return $this->render('posts/posts.html.twig', [
            'controller_name' => 'PostsController',
            'posts' => $blogPosts,
            'user' => $user
        ]);
    }

    // See 👀👀👀👀👀👀
    #[Route('/post-{id}', name: 'see.post')]
    public function seePost(BlogPostsRepository $repository, int $id): Response
    {
        $blogPosts = $repository->find($id);

        return $this->render('posts/seepost.html.twig', [
            'post' => $blogPosts,
        ]);
    }

    // Add 🆕🆕🆕🆕🆕🆕
    #[Route('/posts/create-post', name: 'add.post')]
    public function createPost(Request $request, EntityManagerInterface $em): Response
    {
        $newPost = new BlogPosts();

        $form = $this->createForm(PostsType::class, $newPost);
        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {


            $em->persist($newPost);
            $em->flush();

            return $this->redirectToRoute('app.posts');
        }

        return $this->render('posts/addposts.html.twig', [
            'form' => $form
        ]);
    }



    // Edit ♻️♻️♻️♻️♻️♻️♻️
    #[Route('/posts/edit-{id}', name: 'edit.post')]
    public function editPost(Request $request, BlogPosts $blogPosts, EntityManagerInterface $em): Response
    {


        $form = $this->createForm(PostsType::class, $blogPosts);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($blogPosts);
            $em->flush();
            return $this->redirectToRoute('app.posts');
        }

        return $this->render('posts/updatePosts.html.twig', [
            'form' => $form,
            'blogPosts' => $blogPosts
        ]);
    }

    // Remove 🗑️🗑️🗑️🗑️🗑️🗑️
    #[Route('/delete-post-{id}', name: 'delete.post')]
    public function deletePost(BlogPosts $blogPosts, EntityManagerInterface $em): Response
    {
        $em->remove($blogPosts);
        $em->flush();
        return $this->redirectToRoute('app.post');
    }
}
