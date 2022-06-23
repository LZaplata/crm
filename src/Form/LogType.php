<?php

namespace App\Form;

use App\Entity\Log;
use App\Entity\State;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\DateTimeTzImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\String\u;

class LogType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * LogType constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("created_at", DateType::class, [
                "label" => "log.form.created_at",
                "data" => $options["created_at"],
                "input" => "datetime_immutable",
                "widget" => "single_text",
            ])
            ->add("text", TextareaType::class, [
                "label" => "log.form.text"
            ])
            ->add("state", EntityType::class, [
                "label" => "log.form.state.label",
                "class" => State::class,
                "choice_label" => function ($state) {
                    return $this->translator->trans("state." . u($state->getName())->lower());
                },
                "placeholder" => "log.form.state.placeholder",
                "required" => false
            ])
            ->add("price", NumberType::class, [
                "label" => "log.form.price",
                "required" => false
            ])
            ->add("submit", SubmitType::class, [
                "label" => "log.form.submit",
                "attr" => [
                    "class" => "btn btn-primary btn-sm"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Log::class,
            "created_at" => new \DateTimeImmutable("now")
        ]);
    }
}
