<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Tariff;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TariffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("client", EntityType::class, [
                "label" => "tariff.form.client",
                "class" => Client::class,
                "choice_label" => "name",
                "query_builder" => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder("client")
                        ->orderBy("client.name", "ASC");
                },
            ])
            ->add("name", TextType::class, [
                "label" => "tariff.form.name",
            ])
            ->add("amount", NumberType::class, [
                "label" => "tariff.form.amount",
            ])
            ->add("submit", SubmitType::class, [
                "label" => "tariff.form.submit"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Tariff::class,
            "created_at" => new \DateTimeImmutable("now")
        ]);
    }
}
