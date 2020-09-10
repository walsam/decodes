<?php

namespace DecodesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date')
                ->add('confirmation')
                ->add('client')
                ->add('commanddetails', CollectionType::class, [
                        'entry_type' => CommandDetailsType::class,
                        'entry_options' => [
                            'label' => false
                                ],
                        'by_reference' => false,
                         // this allows the creation of new forms and the prototype too
                        'allow_add' => true,
                        // self explanatory, this one allows the form to be removed
                        'allow_delete' => true
    ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DecodesBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'decodesbundle_command';
    }


}
