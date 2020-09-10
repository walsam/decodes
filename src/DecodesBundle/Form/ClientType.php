<?php

namespace DecodesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname')
                ->add('lastname')
                ->add('telephone')
                ->add('email','Symfony\Component\Form\Extension\Core\Type\EmailType')
                ->add('creditcard')
                ->add('address')
                ->add('city')
                ->add('zipcode')
                ->add('username')
                ->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DecodesBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'decodesbundle_client';
    }


}
