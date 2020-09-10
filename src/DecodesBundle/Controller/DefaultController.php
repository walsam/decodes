<?php

namespace DecodesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $count=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->shopinglistcount($user);
        $this->get('twig')->addGlobal('count', $count);

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('DecodesBundle:Product')->findBy(array(),array('id'=>'DESC'));
        $best=$this->getDoctrine()->getRepository('DecodesBundle:CommandDetails')->bestseller();
        $categories = $em->getRepository('DecodesBundle:Category')->findAll();





        return $this->render('@Decodes/Default/index.html.twig', array(
            'lastproducts' => $products,
            'categories' => $categories,
            'bestproducts' => $best,
        ));
    }
    public function signUpAction()
    {
        return $this->render('@Decodes/Default/signUp.html.twig');
    }

}
