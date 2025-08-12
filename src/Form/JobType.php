<?php

namespace App\Form;

use App\Entity\Action;
use App\Entity\Job;
use App\Entity\Presence;
use App\Entity\Website;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JobType extends AbstractType
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    { }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getDefaultOptions('Job name'))
            ->add('company', TextType::class, $this->getDefaultOptions('Company'))
            ->add('url', TextType::class, $this->getDefaultOptions('Job url'))
            ->add('creation_date', DateType::class, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
            ])
            ->add('application_date', DateType::class, [
                'required' => true,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'html5' => false,
            ])
            ->add('salary', IntegerType::class, $this->getDefaultOptions('Salary', false))
            ->add('asked_salary', IntegerType::class, $this->getDefaultOptions('Salary asked', false))
            ->add('city', TextType::class, $this->getDefaultOptions('City'))
            ->add('website', EntityType::class, [
                'class' => Website::class,
                'choice_label' => 'name',
                'help' => $this->getNewLinkForClass('app_website_new', 'website'),
                'help_html' => true,
            ])
            ->add('presence', EntityType::class, [
                'class' => Presence::class,
                'choice_label' => 'presence',
                'help' => $this->getNewLinkForClass('app_presence_new', 'presence'),
                'help_html' => true,
            ])
            ->add('action', EntityType::class, [
                'class' => Action::class,
                'choice_label' => 'action',
                'multiple' => true,
                'help' => $this->getNewLinkForClass('app_action_new', 'action'),
                'help_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }

    private function getDefaultOptions(string $placeholder, bool $required = true): array
    {
        return [
            'required' => $required,
            'label' => false,
            'attr' => [
                'placeholder' => $placeholder,
            ]
        ];
    }

    private function getNewLinkForClass(string $route, string $entity): string
    {
        return '<a href="'
            . $this->urlGenerator->generate($route, [], $this->urlGenerator::ABSOLUTE_URL)
            . '">Add ' . $entity . '</a>';
    }
}
