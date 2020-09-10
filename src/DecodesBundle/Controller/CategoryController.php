<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Category;
use DecodesBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('DecodesBundle:Category')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }


    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $category = new Category();
        $form = $this->createForm('DecodesBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $category->getPicture();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );
            $category->setPicture($fileName);
            $em->persist($category);
            $em->flush();


            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     */
    public function showAction(Category $category)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);
        $deleteForm = $this->createDeleteForm($category);
        $em = $this->getDoctrine()->getManager();
        $Products = $em->getRepository('DecodesBundle:Product')->findByCategory($category);
        $categories = $em->getRepository('DecodesBundle:Category')->findAll();

        return $this->render('category/show.html.twig', array(
            'categories' => $categories,
            'Products' => $Products,
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $category->setPicture(
            new File($this->getParameter('brochures_directory').'/'.$category->getPicture())
        );
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('DecodesBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $category->getPicture();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );
            $category->setPicture($fileName);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
