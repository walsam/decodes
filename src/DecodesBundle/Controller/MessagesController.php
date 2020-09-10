<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Message controller.
 *
 */
class MessagesController extends Controller
{
    /**
     * Lists all message entities.
     *
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('DecodesBundle:Messages')->findAll();

        return $this->render('messages/index.html.twig', array(
            'messages' => $messages,
        ));
    }

    /**
     * Creates a new message entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);
        $message = new Messages();
        $form = $this->createForm('DecodesBundle\Form\MessagesType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('decodes_contact');
        }

        return $this->render('messages/new.html.twig', array(
            'message' => $message,
            'form' => $form->createView(),
        ));

    }

    /**
     * Finds and displays a message entity.
     *
     */
    public function showAction(Messages $message)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($message);

        return $this->render('messages/show.html.twig', array(
            'message' => $message,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing message entity.
     *
     */
    public function editAction(Request $request, Messages $message)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($message);
        $editForm = $this->createForm('DecodesBundle\Form\MessagesType', $message);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('messages_edit', array('id' => $message->getId()));
        }

        return $this->render('messages/edit.html.twig', array(
            'message' => $message,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a message entity.
     *
     */
    public function deleteAction(Request $request, Messages $message)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $form = $this->createDeleteForm($message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('messages_index');
    }

    /**
     * Creates a form to delete a message entity.
     *
     * @param Messages $message The message entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Messages $message)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('messages_delete', array('id' => $message->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
