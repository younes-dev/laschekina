<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GallerysCrossingCartoonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

          
        ->add('path', FileType::class, array('label' => "form.picture", 'required' => false, 'data_class' => null , 'attr' => array( 'data-file-types'=>"image/jpeg,image/png,image/gif" , 'data-preview'=>"on",'data-field-type' => "bootstrap-file-filed" )))
      ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackBundle\Entity\GallerysCrossingCartoon'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backbundle_gallerys_crossing_cartoon';
    }


}
