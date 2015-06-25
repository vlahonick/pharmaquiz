<?php

namespace PharmaQuiz\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class QuizzerType extends AbstractType
{
    protected $questions;

    public function __construct(array $questions) {
        $this->questions = $questions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $i = 0;
        foreach ($this->questions as $question) {
            $i++;
            $k = 0;
            $letters = range('A', 'Z');
            $answers = array();
            foreach ($question->getAnswers() as $answer) {
                $answers[$answer->getId()] = '<strong>' . $letters[$k] . '.</strong> ' . $answer->getAnswer();
                $k++;
            }
            $builder
                ->add('question' . $question->getId(), 'choice', array(
                    'label' => $i . '. ' . $question->getTitle(),
                    'choices' => $answers,
                    'required' => true,
                    'multiple' => $question->getNumberOfCorrectAnswers() > 1,
                    'expanded' => true,
                ));
        }

        $builder->add('send', 'submit', array(
          'label' => 'Show results',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collection = array();
        foreach ($this->questions as $question) {
            $collection['question' . $question->getId()] = array(
                new NotBlank(array('message' => 'Answer should not be blank.')),
            );
        }
        $collectionConstraint = new Collection($collection);

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint,
        ));
    }

    public function getName()
    {
        return 'quiz_questions';
    }
}
