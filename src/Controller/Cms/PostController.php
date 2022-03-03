<?php


namespace App\Controller\Cms;


use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/admin/post/new', name: 'admin-post-new')]
    public function new( Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        if ($request->getMethod() === 'POST') {
            $em = $doctrine->getManager();
            $post = new Post();
            //Slug
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($request->get('title'));
            $post->setSlug($slug);
            $post->setDescription($request->get('description'));
            $post->setTitle($request->get('title'));


            $errors =  $validator->validate($post);

            if(count($errors) > 0) {
                return $this->render('/cms/post/new.html.twig', [
                    'errors' => $errors,
                ]);
            }
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin-posts');

        }
        return $this->render('/cms/post/new.html.twig');
    }

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
