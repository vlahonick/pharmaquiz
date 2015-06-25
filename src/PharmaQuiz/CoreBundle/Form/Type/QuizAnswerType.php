<?php

namespace PharmaQuiz\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class QuizAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', 'textarea', array(
                'label' => 'Answer *',
                'attr' => array(
                    // minlength
                    'pattern' => '.{2,}',
                ),
                'required' => true,
            ))
            ->add('is_correct', 'checkbox', array(
                'label' => 'Is correct',
                'required' => false,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PharmaQuiz\CoreBundle\Entity\QuizAnswer',
        ));
    }


    public function getName()
    {
        return 'quiz_answer';
    }
}
