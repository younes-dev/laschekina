<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RendezvousType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', null, array('label' => "form.startdate" , 'required' => true, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
                'class' => 'form-control selectDatePicker' ,
                )))
            ->add('endDate', null, array('label' => "form.startdate" , 'required' => true, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
                'class' => 'form-control selectDatePicker' ,
              
                )))
            ->add('title', null, array('label' => 'form.title' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add( 'vip',  EntityType::class, array(
                'attr' => array('class' => 'form-control selectpicker ','data-live-search'=>"true") ,
                'label' => "form.users",
                'class' => 'UserBundle:User',
                'multiple' => false,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('u')
                        ->orderBy("u.username", 'ASC')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%"ROLE_VIP"%');

                }))  
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Rendezvous'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_rendezvous';
    }


}
