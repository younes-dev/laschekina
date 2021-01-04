<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('categoryfr', null, array('label' => 'form.categoryfr' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('categoryen', null, array('label' => 'form.categoryen' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('categoryes', null, array('label' => 'form.categoryes' , 'required' => false, 'attr' => array('class' => 'form-control ')))

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_category';
    }


}
