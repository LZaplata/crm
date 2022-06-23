<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\State;
use App\Entity\Work;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Symfony\Component\String\u;

class WorkType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * WorkType constructor.
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
            ->add("client", EntityType::class, [
                "label" => "work.form.client",
                "class" => Client::class,
                "choice_label" => "name",
                "query_builder" => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder("client")
                        ->orderBy("client.name", "ASC");
                },
            ])
            ->add("subject", TextType::class, [
                "label" => "work.form.subject"
            ])
            ->add("price", NumberType::class, [
                "label" => "work.form.price"
            ])
            ->add("state", EntityType::class, [
                "label" => "work.form.state",
                "class" => State::class,
                "choice_label" => function ($state) {
                    return $this->translator->trans("state." . u($state->getName())->lower());
                },
                "choice_translation_domain" => false,
            ])
            ->add("submit", SubmitType::class, [
                "label" => "work.form.submit"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Work::class,
        ]);
    }
}
