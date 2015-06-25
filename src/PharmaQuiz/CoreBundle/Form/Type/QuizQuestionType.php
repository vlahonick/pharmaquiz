<?php

namespace PharmaQuiz\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class QuizQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'textarea', array(
                'label' => 'Question *',
                'attr' => array(
                    // minlength
                    'pattern' => '.{2,}',
                    'maxlength' => 255,
                ),
                'required' => true,
            ))
            ->add('category', 'entity', array(
                'class' => 'PharmaQuizCoreBundle:QuizCategory',
                'property' => 'title',
                'label' => 'Topic *',
            ))
            ->add('answers', 'collection', array(
                'type' => new QuizAnswerType(),
                'required' => true,
            ))
            ->add('send', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PharmaQuiz\CoreBundle\Entity\QuizQuestion',
        ));
    }


    public function getName()
    {
        return 'quiz_question';
    }
}
