<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Country;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('first_name')
            ->add('last_name')
            ->add('address')
            ->add('address_bis')
            ->add('city')
            ->add('postcode')
            ->add('phone')
            ->add('email')
            ->add('country', EntityType::class, array(
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'multiple'  => false,
                    ))
            // ->add('shipping')
            // ->add('product',EntityType::class, array(
            //         'class' => Product::class,
            //         'choice_label' => 'id',
            //         'multiple'  => false,
            //         ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
