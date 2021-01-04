<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityRepository;

class PersonnageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder

            ->add('nom', null, array('label' => 'form.nom' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('prenom', null, array('label' => 'form.prenom' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('origin', null, array('label' => 'form.pays' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('image', FileType::class, array('label' => "form.picture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
            ->add('twitter', null, array('label' => 'form.twitter' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('facebook', null, array('label' => 'form.facebook' , 'required' => false, 'attr' => array('class' => 'form-control ')))

            ->add( 'category',  EntityType::class, array(
                'attr' => array('class' => 'form-control selectpicker ','data-live-search'=>"true") ,
                'label' => "form.category",
                'class' => 'BackBundle:Category',
                'multiple' => true,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                        ->orderBy("c.id", 'DESC');

                }))
            ->add( 'activity',  EntityType::class, array(
                'attr' => array('class' => 'form-control selectpicker ','data-live-search'=>"true") ,
                'label' => "form.activity",
                'class' => 'BackBundle:Activity',
                'multiple' => true,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('a')
                        ->orderBy("a.id", 'DESC');

                }))
            ->add('description', null, array('label' => 'form.description' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('mot', null, array('label' => 'form.mot' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions ( OptionsResolver $resolver )
    {
        $resolver->setDefaults ( array(
            'data_class' => 'BackBundle\Entity\Personnage'
        ) );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix ()
    {
        return 'backbundle_personnage';
    }


}
