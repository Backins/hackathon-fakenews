<?php

namespace App\Controller\Front;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="app_front_blog_")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('front/blog/index.html.twig', [
            'blogs' => $blogRepository->findBy(['published' => true]),
        ]);
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Blog $blog): Response
    {
        return $this->render('front/blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

}
