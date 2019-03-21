<?php

namespace App\Controller;

use App\Entity\TargetSource;
use App\Form\TargetSourceType;
use App\Repository\TargetSourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/target/source")
 * @IsGranted("ROLE_ADMIN")
 */
class TargetSourceController extends AbstractController
{
    /**
     * @Route("/", name="target_source_index", methods={"GET"})
     */
    public function index(TargetSourceRepository $targetSource): Response
    {
        return $this->render('target_source/index.html.twig', [
            'target_sources' => $targetSource->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="target_source_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $targetSource = new TargetSource();
        $form = $this->createForm(TargetSourceType::class, $targetSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($targetSource);
            $entityManager->flush();

            return $this->redirectToRoute('target_source_index');
        }

        return $this->render('target_source/new.html.twig', [
            'target_source' => $targetSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="target_source_show", methods={"GET"})

    public function show(TargetSource $targetSource): Response
    {
        return $this->render('target_source/show.html.twig', [
            'target_source' => $targetSource,
        ]);
    }*/

    /**
     * @Route("/{id}/edit", name="target_source_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TargetSource $targetSource): Response
    {
        $form = $this->createForm(TargetSourceType::class, $targetSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('target_source_index', [
                'id' => $targetSource->getId(),
            ]);
        }

        return $this->render('target_source/edit.html.twig', [
            'target_source' => $targetSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="target_source_delete", methods={"GET"})
     */
    public function delete(Request $request, TargetSource $targetSource): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($targetSource);
        $entityManager->flush();

        return $this->redirectToRoute('target_source_index');
    }
}
