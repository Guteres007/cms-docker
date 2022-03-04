<?php


namespace App\Controller\Cms;


use App\Entity\Post;
use App\Repository\PostRepository;
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
            $title = $request->get('title');
            $description =    $request->get('description');

            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($title);

            $post = new Post($title,$description,$slug);
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
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();

        return $this->render('/cms/post/index.html.twig', compact('posts'));
    }

    #[Route('/admin/post/{id}/edit', name: 'admin-post-edit')]
    public function edit($id, Request $request, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        if ($request->getMethod() === 'POST') {

            $post->setDescription($request->get('description'));
            $post->setTitle($request->get('title'));
            $postRepository->update($post);

            return $this->redirectToRoute('admin-posts');
        }

        return $this->render('/cms/post/edit.html.twig', compact('post'));
    }
}
