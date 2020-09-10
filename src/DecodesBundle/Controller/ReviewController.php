<?php

namespace DecodesBundle\Controller;

use DecodesBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\IsNull;

/**
 * Review controller.
 *
 */
class ReviewController extends Controller
{
    /**
     * Lists all review entities.
     *
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);

        $reviews = $em->getRepository('DecodesBundle:Review')->findAll();

        return $this->render('review/index.html.twig', array(
            'reviews' => $reviews,
        ));
    }

    /**
     * Creates a new review entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $review = new Review();
        $form = $this->createForm('DecodesBundle\Form\ReviewType', $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $review->getId()));
        }

        return $this->render('review/new.html.twig', array(
            'review' => $review,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a review entity.
     *
     */
    public function showAction(Review $review)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $deleteForm = $this->createDeleteForm($review);

        return $this->render('review/show.html.twig', array(
            'review' => $review,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing review entity.
     *
     */
    public function editAction(Request $request, Review $review)
    {
        $deleteForm = $this->createDeleteForm($review);
        $editForm = $this->createForm('DecodesBundle\Form\ReviewType', $review);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('review_edit', array('id' => $review->getId()));
        }

        return $this->render('review/edit.html.twig', array(
            'review' => $review,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a review entity.
     *
     */
    public function deleteAction(Request $request, Review $review)
    {
        $form = $this->createDeleteForm($review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($review);
            $em->flush();
        }

        return $this->redirectToRoute('review_index');
    }

    /**
     * @Route("/AddReview" ,name="AddReview")
     * @Method({"GET", "POST"})
     */
    public function AddReview(Request $request)
    {
        if($request->request->get("productID")!=null)
        {

            $em = $this->getDoctrine()->getManager();

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $product = $em->getRepository('DecodesBundle:Product')->findOneBy([
                'id' => $request->request->get("productID")
            ]);
            $review = new Review();
            $oldReviews = $em->getRepository('DecodesBundle:Review')->findByUser($user,$product);
            if ($oldReviews == Null ){
                $review->setReview($request->request->get("rating"));
                $review->setDescription($request->request->get("description"));
                $review->setProduit($product);
                $review->setClient($user);
                $em->persist($review);
                $em->flush();
            }
            else{
                foreach ($oldReviews as $oldReview) {
                    $old = $em->getRepository('DecodesBundle:Review')->findOneBy([
                        'id' => $oldReview,
                    ]);
                    $em->remove($old);
                }
                $em->flush();
                $review->setReview($request->request->get("rating"));
                $review->setDescription($request->request->get("description"));
                $review->setProduit($product);
                $review->setClient($user);
                $em->persist($review);
                $em->flush();

            }

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }
        else
            return $this->redirectToRoute('decodes_homepage');

    }

    /**
     * Creates a form to delete a review entity.
     *
     * @param Review $review The review entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Review $review)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('review_delete', array('id' => $review->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
