<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CrosswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('question', null, array('label' => 'form.question' , 'required' => true, 'attr' => array('class' => 'form-control ')))
            ->add('reponse', null, array('label' => 'form.reponse' , 'required' => true, 'attr' => array('class' => 'form-control ')))
            ->add('description', null, array('label' => 'form.description' , 'required' => false, 'attr' => array('class' => 'form-control' , 'maxlength' =>"35")))

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Crossword'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_crossword';
    }


}
