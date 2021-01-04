<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder
            ->add('telephone', null, array('label' => "form.telephone", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('twitter', null, array('label' => "form.twitter", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('caPub', null, array('label' => "form.caPub", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('pays', CountryType::class, array( 'label' => "form.pays", 'required' => false,'attr' => array('class' => 'form-control ' )))
            ->add('listamis', ChoiceType::class, array(
                'choices' => array('form.publier' => true, 'form.non_publier' => false),
                'label' => "form.listamis", 'required' => false,'attr' => array('class' => 'form-control ')))
             ->add('showposotion', ChoiceType::class, array(
                'choices' => array('form.publier' => true, 'form.non_publier' => false),
                'label' => "form.showposotion", 'required' => false,'attr' => array('class' => 'form-control ')))
             ->add('showmapphoto', ChoiceType::class, array(
                'choices' => array('form.publier' => true, 'form.non_publier' => false),
                'label' => "form.showmapphoto", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('facebook', null, array('label' => "form.facebook", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('instagram', null, array('label' => "form.instagram", 'required' => false,'attr' => array('class' => 'form-control ')))
            ->add('datenaissance',  DateType::class, array('label' => "form.datenaissance" , 'required' => false, 'widget' => 'single_text','format' => 'dd/MM/yyyy' , 'attr' => array(
                'class' => 'form-control selectDatePicker' ,
                )))
            ->add('nom', null, array('label' => "form.nom",  'required' => false,'attr' => array('class' => 'form-control')))
            ->add('prenom', null, array('label' => "form.prenom",  'required' => false,'attr' => array('class' => 'form-control')))
            ->add('ville', null, array('label' => "form.ville",  'required' => false,'attr' => array('class' => 'form-control')))
            ->add('adresse', null, array('label' => "form.adresse", 'required' => false, 'attr' => array('class' => 'form-control')))
            ->add('codepostal', null, array('label' => "form.codepostal", 'required' => false, 'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array('label' => "form.email",  'required' => true,'attr' => array('class' => 'form-control')))

            ->add('etatMessenger', ChoiceType::class, array(
                'choices' => array('form.active' => true, 'form.non_active' => false),
                'label' => "form.etatMessenger", 'required' => false,'attr' => array('class' => 'form-control ')))

            ->add('username', null, array('label' => "form.username", 'required' => true, 'attr' => array('class' => 'form-control')))
            ->add('pictureprofil', FileType::class, array('label' => "form.pictureprofil", 'required' => false, 'data_class' => null , 'attr' => array('class' => 'form-control' ,'style' =>'display: none;')))
            ->add('picturecover', FileType::class, array('label' => "form.picturecover", 'required' => false, 'data_class' => null , 'attr' => array('class' => 'form-control' ,'style' =>'display: none;')))
          
        ;
    }

    public function getName() {
        return 'app_user_profile';
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }
}
