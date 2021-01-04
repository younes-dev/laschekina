<?php
/**
 * Created by PhpStorm.
 * User: Technetmedia
 * Date: 21/05/2019
 * Time: 08:57
 */

namespace PaymentBundle\Form\Type;

use PaymentBundle\Entity\GatewayConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

final class PaypalGatewayConfigType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('factoryName', TextType::class, [
                'disabled' => true,
                'data' => 'paypal_express_checkout',
            ])
            ->add('gatewayName', TextType::class)
            ->add('config', ConfigPaypalGatewayConfigType::class, [
                'label' => false,
                'auto_initialize' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GatewayConfig::class,
        ]);
    }
}