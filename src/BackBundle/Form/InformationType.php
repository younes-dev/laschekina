<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class InformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm ( FormBuilderInterface $builder , array $options )
    {
        $builder

        ->add('descriptionPub', null, array('label' => 'form.descriptionPub' , 'required' => false, 'attr' => array('class' => 'form-control ')))
        ->add('descriptionContactfr', null, array('label' => 'form.descriptionContactfr' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
        ->add('descriptionContacten', null, array('label' => 'form.descriptionContacten' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
        ->add('descriptionContactes', null, array('label' => 'form.descriptionContactes' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
        ->add('descriptionpopupfr', null, array('label' => 'form.descriptionpopupfr' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
        ->add('descriptionpopupen', null, array('label' => 'form.descriptionpopupen' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('descriptionpopupes', null, array('label' => 'form.descriptionpopupes' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('descriptionfr', null, array('label' => 'form.descriptionfr' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('descriptionen', null, array('label' => 'form.descriptionen' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('descriptiones', null, array('label' => 'form.descriptiones' , 'required' => false, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('facebook', null, array('label' => 'form.facebook' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('twitter', null, array('label' => 'form.twitter' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('linkedin', null, array('label' => 'form.linkedin' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('googleplus', null, array('label' => 'form.googleplus' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('email', null, array('label' => 'form.email' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('phone', null, array('label' => 'form.telephone' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('adress', null, array('label' => 'form.adresse' , 'required' => false, 'attr' => array('class' => 'form-control ')))
           // ->add('delivery', null, array('label' => 'Prix de Livraison' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('tax', NumberType::class, array('label' => 'Tax' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('backgroundpicture', FileType::class, array('label' => "form.backgroundpicture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions ( OptionsResolver $resolver )
    {
        $resolver->setDefaults ( array(
            'data_class' => 'BackBundle\Entity\Information'
        ) );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix ()
    {
        return 'backbundle_information';
    }


}
