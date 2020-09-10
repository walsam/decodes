<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Command;
use DecodesBundle\Entity\CommandDetails;
use DecodesBundle\Entity\Product;
use DecodesBundle\Form\CommandType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Command controller.
 *
 */
class CommandController extends Controller
{
    /**
     * Lists all command entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $commands = $em->getRepository('DecodesBundle:Command')->findAll();
        $commands= array_reverse($commands);

        return $this->render('command/index.html.twig', array(
            'commands' => $commands,
        ));
    }

    /**
     * @Route("ConfirmeCom" ,name="ConfirmeCom")
     * @Method({"GET", "POST"})
     */
    public function ConfirmeCom(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $com=$em->getRepository('DecodesBundle:Command')->findOneBy([
            'id' => $request->request->get("id"),
        ]);
        $client=$em->getRepository('DecodesBundle:Client')->findOneBy(['username' => $request->request->get("client") ]);
        $com->setClient($client);
        $com->setConfirmation(true);
        $com->setDate(new \DateTime());
        $em->persist($com);
        $em->flush();

        return $this->redirectToRoute('command_index');
    }
    /**
     * @Route("deleteCom" ,name="deleteCom")
     * @Method({"GET", "POST"})
     */
    public function DeleteCom(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $com=$em->getRepository('DecodesBundle:Command')->findOneBy([
            'id' => $request->request->get("id"),
        ]);
        $em->remove($com);
        $em->flush();

        return $this->redirectToRoute('command_index');
    }


    /**
     * Creates a new command entity.
     *
     */
    public function newAction(Request $request)
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($command);
            $em->flush();

            return $this->redirectToRoute('command_show', array('id' => $command->getId()));
        }

        return $this->render('command/new.html.twig', array(
            'command' => $command,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a command entity.
     *
     */
    public function showAction(Command $command)
    {
        $deleteForm = $this->createDeleteForm($command);

        return $this->render('command/show.html.twig', array(
            'command' => $command,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing command entity.
     *
     */
    public function editAction(Request $request, Command $command)
    {
        $deleteForm = $this->createDeleteForm($command);
        $editForm = $this->createForm('DecodesBundle\Form\CommandType', $command);
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('command_edit', array('id' => $command->getId()));
        }

        return $this->render('command/edit.html.twig', array(
            'command' => $command,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Deletes a command entity.
     *
     */
    public function deleteAction(Request $request, Command $command)
    {
        $form = $this->createDeleteForm($command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($command);
            $em->flush();
        }

        return $this->redirectToRoute('command_index');
    }

    public function newCommand(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $command = new Command();
        $command->setClient($user);
        $command->setDate(time());
        $command->setConfirmation(false);
        $em->persist($command);
        $em->flush();
    }



    /**
     * Creates a form to delete a command entity.
     *
     * @param Command $command The command entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Command $command)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('command_delete', array('id' => $command->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
