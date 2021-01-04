<?php
/**
 * Created by PhpStorm.
 * User: Technetmedia
 * Date: 21/05/2019
 * Time: 08:57
 */

namespace PaymentBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ConfigPaypalGatewayConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sandbox', CheckboxType::class)
            ->add('username', TextType::class)
            ->add('password', TextType::class)
            ->add('signature', TextType::class)
        ;
    }
}