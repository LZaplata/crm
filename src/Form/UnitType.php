<?php

namespace App\Form;

use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("created_at", DateType::class, [
                "label" => "unit.form.created_at",
                "data" => $options["created_at"],
                "input" => "datetime_immutable",
                "widget" => "single_text",
            ])
            ->add("text", TextareaType::class, [
                "label" => "unit.form.text"
            ])
            ->add("amount", NumberType::class, [
                "label" => "unit.form.amount"
            ])
            ->add("submit", SubmitType::class, [
                "label" => "unit.form.submit",
                "attr" => [
                    "class" => "btn btn-primary btn-sm"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Unit::class,
            "created_at" => new \DateTimeImmutable("now"),
        ]);
    }
}
