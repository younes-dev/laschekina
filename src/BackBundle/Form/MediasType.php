<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class MediasType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('sowPub', ChoiceType::class, ['attr' => array('class' => 'form-control ') ,
                    'choices' => [
                        'active' => true,
                        'inactive' => false
                    ]       ])
            ->add('urlradio', null, array('label' => 'form.urlradio' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('titleHome', null, array('label' => 'form.titleHome' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('title', null, array('label' => 'form.title' , 'required' => true, 'attr' => array('class' => 'form-control ')))
            ->add('channel', null, array('label' => 'form.channel' , 'required' => true, 'attr' => array('class' => 'form-control ')))
            ->add('urlvideo', null, array('label' => 'form.sourcevideo' , 'required' => false, 'attr' => array('class' => 'form-control ')))
            ->add('shortdescription', null, array('label' => 'form.shortdescription' , 'required' => true, 'attr' => array('class' => 'form-control ')))
            ->add('detaileddescription', null, array('label' => 'form.description' , 'required' => true, 'attr' => array('class' => 'form-control  ckeditor')))
            ->add('picture', FileType::class, array('label' => "form.picture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
            ->add('pictureHome', FileType::class, array('label' => "form.pictureHome", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
            ->add('backgroundpicture', FileType::class, array('label' => "form.backgroundpicture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
            ->add('startdate', null, array('label' => "form.startdate" , 'required' => false, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
                'class' => 'form-control selectDatePicker' ,
                )))
                
                ->add('enddate', null, array('label' => "form.enddate" , 'required' => false, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
                    'class' => 'form-control selectDatePicker' ,
                    )))
                    
                        ->add('startTime', null, array(
                'label' => "form.startTime",
                'attr' => array('class' => 'form-control') ,
               'required' => false,

            ))
            ->add( 'personnage',  EntityType::class, array(
                'attr' => array('class' => 'form-control   chosen-select ',
                'data-placeholder'=>"Liste des personnages ..."
                ) ,
                'label' => "page.personnage",
                'class' => 'BackBundle:Personnage',
                'multiple' => true,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                        ->orderBy("c.id", 'DESC');
                      //  ->setMaxResults ( 200 );

                }))
            ->add( 'vod',  EntityType::class, array(
                'attr' => array('class' => 'form-control ',
                'data-placeholder'=>"Liste des Vods ..."
                ) ,
                'label' => "page.vod",
                'class' => 'BackBundle:Vod',
                'multiple' => false,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                        ->orderBy("c.id", 'DESC');
                      //  ->setMaxResults ( 200 );

                }))

            ->add( 'typeContentMedia',  EntityType::class, array(
                'attr' => array('class' => 'form-control ',
                'data-placeholder'=>"Liste des Vods ..."
                ) ,
                'label' => "Type de Media",
                'class' => 'BackBundle:TypeContentMedia',
                'multiple' => false,
                'required' => false ,
                'query_builder' => function(EntityRepository $er ) {
                    return $er->createQueryBuilder('c')
                        ->orderBy("c.id", 'DESC');
                      //  ->setMaxResults ( 200 );

                }))
            ->add( 'users',  EntityType::class, array(
               'attr' => array('class' => 'form-control  chosen-select ',
               'data-placeholder'=>"Liste des acteurs ..."
               ) ,
                'label' => "form.users",
                'class' => 'UserBundle:User',
                'multiple' => true,
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
            'data_class' => 'BackBundle\Entity\Medias'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_medias';
    }


}
