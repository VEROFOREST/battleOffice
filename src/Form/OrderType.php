<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('id_api')
            ->add('is_paid');
        
            // ->add('client',EntityType::class, array(
            //         'class' => Client::class,
            //         'choice_label' => 'id',
            //         'multiple'  => false,))

            // ->add('product',EntityType::class, array(
            //         'class' => Product::class,
            //         'choice_label' => 'id',
            //         'multiple'  => false,));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
