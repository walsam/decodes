<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Admin controller.
 *
 */
class AdminController extends Controller
{
    /**
     * Lists all admin entities.
     *
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $admins = $em->getRepository('DecodesBundle:Admin')->findAll();

        return $this->render('admin/index.html.twig', array(
            'admins' => $admins,
        ));
    }

    /**
     * Creates a new admin entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $admin = new Admin();
        $form = $this->createForm('DecodesBundle\Form\AdminType', $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('admin_show', array('id' => $admin->getId()));
        }

        return $this->render('admin/new.html.twig', array(
            'admin' => $admin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a admin entity.
     *
     */
    public function showAction(Admin $admin)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($admin);

        return $this->render('admin/show.html.twig', array(
            'admin' => $admin,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing admin entity.
     *
     */
    public function editAction(Request $request, Admin $admin)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($admin);
        $editForm = $this->createForm('DecodesBundle\Form\AdminType', $admin);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_edit', array('id' => $admin->getId()));
        }

        return $this->render('admin/edit.html.twig', array(
            'admin' => $admin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a admin entity.
     *
     */
    public function deleteAction(Request $request, Admin $admin)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admin);
            $em->flush();
        }

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Creates a form to delete a admin entity.
     *
     * @param Admin $admin The admin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Admin $admin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $admin->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
