<?php


namespace App\Controller\Cms;


use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    #[Route('/admin/posts', name: 'admin-posts')]
    public function index(ManagerRegistry $doctrine)
    {
        $posts = $doctrine->getRepository(Post::class)->findAll();
        return $this->render('/cms/post/index.html.twig', compact('posts'));
    }

    #[Route('/admin/post/{id}/edit', name: 'admin-post-edit')]
    public function edit($id, Request $request, ManagerRegistry $doctrine)
    {
        $post = $doctrine->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        if ($request->getMethod() === 'POST') {
            $em = $doctrine->getManager();
            $post->setDescription($request->get('description'));
            $post->setTitle($request->get('title'));
            $em->flush();
            return $this->redirectToRoute('admin-posts');
        }

        return $this->render('/cms/post/edit.html.twig', compact('post'));
    }
}
