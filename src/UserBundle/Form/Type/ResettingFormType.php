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

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ResettingFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'),
                array(
             'attr' => array('class' => 'form-control '),
            'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
            'first_options' => array('label' => "form.new_password"  , 'attr' => array('class' => 'form-control ' ,  'placeholder' =>   'form.new_password')),
            'second_options' => array('label' => "form.new_password_confirmation" , 'attr' => array('class' => 'form-control ' ,  'placeholder' =>   'form.new_password_confirmation')),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    public function getName() {
        return 'app_user_Resetting';
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\ResettingFormType';
    }

}
