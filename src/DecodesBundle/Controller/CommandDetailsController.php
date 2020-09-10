<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\CommandDetails;
use DecodesBundle\Entity\Command;
use DecodesBundle\Controller\CommandController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Commanddetail controller.
 *
 */
class CommandDetailsController extends Controller
{
    /**
     * Lists all commandDetail entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commandDetails = $em->getRepository('DecodesBundle:CommandDetails')->findAll();

        return $this->render('commanddetails/index.html.twig', array(
            'commandDetails' => $commandDetails,
        ));
    }

    /**
     * Creates a new commandDetail entity.
     *
     */
    public function newAction(Request $request)
    {
        $commandDetail = new CommandDetails();
        $form = $this->createForm('DecodesBundle\Form\CommandDetailsType', $commandDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commandDetail);
            $em->flush();

            return $this->redirectToRoute('commanddetails_show', array('id' => $commandDetail->getId()));
        }

        return $this->render('commanddetails/new.html.twig', array(
            'commandDetail' => $commandDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commandDetail entity.
     *
     */
    public function showAction(CommandDetails $commandDetail)
    {
        $deleteForm = $this->createDeleteForm($commandDetail);

        return $this->render('commanddetails/show.html.twig', array(
            'commandDetail' => $commandDetail,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commandDetail entity.
     *
     */
    public function editAction(Request $request, CommandDetails $commandDetail)
    {
        $deleteForm = $this->createDeleteForm($commandDetail);
        $editForm = $this->createForm('DecodesBundle\Form\CommandDetailsType', $commandDetail);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ShowCart');
        }

        return $this->render('DecodesBundle:Default:cart.html.twig', array(
            'commandDetail' => $commandDetail,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commandDetail entity.
     *
     */
    public function deleteAction(Request $request, CommandDetails $commandDetail)
    {
        $form = $this->createDeleteForm($commandDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commandDetail);
            $em->flush();
        }

        return $this->redirectToRoute('ShowCart');
    }

    /**
     * @Route("/ShowCart" ,name="ShowCart")
     * @Method({"GET", "POST"})
     */
    public function ShowCart(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);

        $en = $this->getDoctrine()->getManager();
        $commandnew = $en->getRepository('DecodesBundle:Command')->findnew($user);
        $commandclosed = $en->getRepository('DecodesBundle:Command')->findclosed($user);


        return $this->render('@Decodes/Default/cart.html.twig', array(
            'newcommands' => $commandnew,
            'closedcommands' => $commandclosed,
        ));


    }

    /**
     * @Route("DeleteFromCart" ,name="DeleteFromCart")
     * @Method({"GET", "POST"})
     */
    public function DeleteFromCart(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $comdet=$em->getRepository('DecodesBundle:CommandDetails')->findOneBy([
            'id' => $request->request->get("productID"),
        ]);
        $em->remove($comdet);
        $em->flush();

        return $this->redirectToRoute('ShowCart');
    }

    /**
     * @Route("UpdateCart" ,name="UpdateCart")
     * @Method({"GET", "POST"})
     */
    public function UpdateCart(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $comdet=$em->getRepository('DecodesBundle:CommandDetails')->findOneBy([
            'id' => $request->request->get("productID"),
        ]);
        $comdet->setNumber($request->request->get("number"));
        $cost=$request->request->get("PriceUni")*$request->request->get("number");
        $comdet->setCost($cost);
        $em->persist($comdet);
        $em->flush();

        return $this->redirectToRoute('ShowCart');
    }

    /**
     * @Route("/securityPayment/AddToCard" ,name="AddToCard")
     * @Method({"GET", "POST"})
     */
    public function AddToCard(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($request->request->get("productID")!=null)
        {
            //ila dkhlu l lien 3an tari9 ADD to card button li f Product Show

            $em = $this->getDoctrine()->getManager();



            $product = $em->getRepository('DecodesBundle:Product')->findOneBy([
                'id' => $request->request->get("productID")
            ]);
            $command = $em->getRepository('DecodesBundle:Command')->findOneBy([
                'client' => $user->getId(),
                'confirmation'=>0,
            ]);
            if($command!=null)
            {
                if($command->getConfirmation()=='0')
                {
                    //khra nar o dir new command o fax tcreeha 9lb 3liha f basedonne o dirha f $commandDetail o sf ||| kidir tcree dir $command = new Command() aprs tb9a dir set... || kidir tjbd l id dyalha dir wa7d l field f table ykun 7ta hwa unique o dir wa7d fl code hna wa7d ra9m random o 7to fl table aprs 9lb 3la l commaand b dak l ra9m random aprs rdh null! bax matl9ax xi moxkil mra okhra o jbd l id o rak tem! xi 7aja 7slt fiha ani hna! + gha 3gzt ndiru 7it fih tamara xD khasni ncree f entity o ndir des commands etc xD o lmhm rak tem xD
                    $commandDetail = new CommandDetails();


                    $cost=$request->request->get("CommandPrice")*$request->request->get("CommandQuantity");



                    $commandDetail->setNumber($request->request->get("CommandQuantity"));
                    $commandDetail->setCost($cost);
                    $commandDetail->setCommand($command);
                    $commandDetail->setProduit($product);
                    $em->persist($commandDetail);
                    $em->flush();

                }
                else{

                }
            }
            else
            {
                //nfs xi diru hna dir new command ... read above ^
                $ep = $this->getDoctrine()->getManager();

                $command = new Command();
                $command->setClient($user);
                $time = new \DateTime();
                $command->setDate($time);
                $command->setConfirmation(false);
                $ep->persist($command);
                $ep->flush();
                $com = $ep->getRepository('DecodesBundle:Command')->findOneBy([
                    'client' => $user->getId(),
                    'confirmation' => 0,
                ]);

                $commandDetail = new CommandDetails();


                $cost=$request->request->get("CommandPrice")*$request->request->get("CommandQuantity");



                $commandDetail->setNumber($request->request->get("CommandQuantity"));
                $commandDetail->setCost($cost);
                $commandDetail->setCommand($com);
                $commandDetail->setProduit($product);
                $ep->persist($commandDetail);
                $ep->flush();
            }

            //return xof nta fin bdir tdihum! o matnsax t7iyd die! o dir blastha return ...
            return $this->redirectToRoute('product_index');
        }
        else
            //ila dkhlu l lien 3an tari9 URL  bla maydkhlo les donnees! hna dir redirection l home! ola page not found!
            return $this->redirectToRoute('decodes_homepage');

    }

    /**
     * Creates a form to delete a commandDetail entity.
     *
     * @param CommandDetails $commandDetail The commandDetail entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommandDetails $commandDetail)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commanddetails_delete', array('id' => $commandDetail->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
