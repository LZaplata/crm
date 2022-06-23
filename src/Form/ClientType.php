<?php

namespace App\Form;

use App\Entity\Client;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "client.form.name"
            ])
            ->add("email", EmailType::class, [
                "label" => "client.form.email"
            ])
            ->add("businessId", TextType::class, [
                "label" => "client.form.business_id",
                "required" => false,
            ])
            ->add("submit", SubmitType::class, [
                "label" => "client.form.submit"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Client::class,
        ]);
    }
}
