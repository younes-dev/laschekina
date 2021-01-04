<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('activityfr', null, array('label' => 'form.activityfr' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('activityen', null, array('label' => 'form.activityen' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('activityes', null, array('label' => 'form.activityes' , 'required' => false, 'attr' => array('class' => 'form-control ')))

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Activity'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_activity';
    }


}
