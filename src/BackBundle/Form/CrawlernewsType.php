<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class CrawlernewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', null, array('label' => 'form.title' , 'required' => true, 'attr' => array('class' => 'form-control ')))
        ->add('channel', null, array('label' => 'form.channel' , 'required' => false, 'attr' => array('class' => 'form-control ')))
        ->add('shortdescription', null, array('label' => 'form.shortdescription' , 'required' => true, 'attr' => array('class' => 'form-control ')))
        ->add('description', null, array('label' => 'form.description' , 'required' => true, 'attr' => array('class' => 'form-control  ckeditor')))
        ->add('picture', FileType::class, array('label' => "form.picture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
        ->add('pays', null, array('label' => 'form.pays' , 'required' => false, 'attr' => array('class' => 'form-control ')))
        ->add('dateStart', null, array('label' => "form.startdate" , 'required' => true, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
            'class' => 'form-control selectDatePicker' ,
            )))
        ->add('startTime', null, array(
            'label' => "form.startTime",
            'attr' => array('class' => 'form-control') ,
           'required' => true,

       ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\Crawlernews'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_Crawlernews';
    }


}
