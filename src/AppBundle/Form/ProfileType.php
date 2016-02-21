<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'choices' => ['Male' => 'm', 'Female' => 'f'],
                    'required' => false,
                    'placeholder' => 'Choose your gender',
                    'empty_data' => null,
                ]
            )
            ->add('homepage', UrlType::class, ['required' => false])
            ->add('birthday', BirthdayType::class, ['required' => false])
            ->add('avatar', FileType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_profile_type';
    }
}
