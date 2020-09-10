<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('DecodesBundle:Client')->findAll();

        return $this->render('client/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $client = new Client();
        $form = $this->createForm('DecodesBundle\Form\ClientType', $client);
        $form->handleRequest($request);
        $message = '5';
        $error= '';
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $password = $passwordEncoder->encodePassword($client, $client->getPassword());
                $client->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                $em->flush();
                $message = '1';
            } catch (\Exception $e) {
                $message = '0';
                $error = 'username deja utiliser';
            }
            if ($message=='1'){
                return $this->redirectToRoute('login');
            }

        }

        return $this->render('@Decodes/Default/signUp.html.twig', array(
            'error' => $error,
            'client' => $client,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a client entity.
     *
     */
    public function showAction(Client $client)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);
        $em = $this->getDoctrine()->getManager();
        $reviews=$em->getRepository('DecodesBundle:Review')->findBy(['client' => $client]);
        $deleteForm = $this->createDeleteForm($client);
        return $this->render('client/show.html.twig', array(
            'reviews' => $reviews,
            'client' => $client,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     */
    public function editAction(Request $request, Client $client, UserPasswordEncoderInterface $passwordEncoder)
    {
        $editForm = $this->createForm('DecodesBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);
        $deleteForm = $this->createDeleteForm($client);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $passwordEncoder->encodePassword($client, $client->getPassword());
            $client->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_edit', array('id' => $client->getId()));
        }

        return $this->render('client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     */
    public function editXAction(Request $request, Client $client, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);

        $editForm = $this->createForm('DecodesBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $passwordEncoder->encodePassword($client, $client->getPassword());
            $client->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('decodes_homepage');
        }

        return $this->render('@Decodes/Default/account.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
        ));


    }

    /**
     * Deletes a client entity.
     *
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('id' => $client->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
