<?php

namespace App\Form;

use App\Entity\DatabaseCredential;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Environment;

class DatabaseCredentialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('databaseName')
            ->add('user')
            ->add('port')
            ->add('host')
            ->add('password')
            ->add('environment', EntityType::class, [
                'class' => Environment::class,
                'choice_label' => 'name',
                'placeholder' => '-- None --',
                'required' => false
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DatabaseCredential::class,
        ]);
    }
}
