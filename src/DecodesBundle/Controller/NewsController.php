<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * News controller.
 *
 */
class NewsController extends Controller
{
    /**
     * Lists all news entities.
     *
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('DecodesBundle:News')->findAll();

        return $this->render('news/index.html.twig', array(
            'news' => $news,
        ));

    }

    /**
     * Creates a new news entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $news = new News();
        $form = $this->createForm('DecodesBundle\Form\NewsType', $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            return $this->redirectToRoute('news_show', array('id' => $news->getId()));
        }

        return $this->render('news/new.html.twig', array(
            'news' => $news,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a news entity.
     *
     */
    public function showAction(News $news)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);
        $deleteForm = $this->createDeleteForm($news);

        return $this->render('news/show.html.twig', array(
            'news' => $news,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing news entity.
     *
     */
    public function editAction(Request $request, News $news)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($news);
        $editForm = $this->createForm('DecodesBundle\Form\NewsType', $news);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('news_edit', array('id' => $news->getId()));
        }

        return $this->render('news/edit.html.twig', array(
            'news' => $news,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a news entity.
     *
     */
    public function deleteAction(Request $request, News $news)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($news);
            $em->flush();
        }

        return $this->redirectToRoute('news_index');
    }

    /**
     * Creates a form to delete a news entity.
     *
     * @param News $news The news entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(News $news)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('news_delete', array('id' => $news->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
