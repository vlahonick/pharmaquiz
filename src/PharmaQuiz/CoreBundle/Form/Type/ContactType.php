<?php

namespace PharmaQuiz\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Name *',
                'attr' => array(
                    // minlength
                    'pattern' => '.{2,}',
                ),
            ))
            ->add('email', 'email', array(
                'label' => 'Email *',
                'attr' => array(
                    // minlength
                    'pattern' => '.{5,}',
                ),
            ))
            ->add('subject', 'choice', array(
                'label' => 'Subject *',
                'choices' => array(
                    'info' => 'Inaccurate information',
                    'bug' => 'Bug',
                    'copyrights' => 'Copyright',
                    'volunteer' => 'Help',
                    'other' => 'Other',
                ),
            ))
            ->add('message', 'textarea', array(
            'label' => 'Message *',
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    // minlength
                    'pattern' => '.{5,}',
                ),
            ))
            ->add('send', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Name should not be blank.')),
                new Length(array('min' => 2)),
            ),
            'email' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
                new Email(array('message' => 'Invalid email address.')),
            ),
            'subject' => array(
                new NotBlank(array('message' => 'Subject should not be blank.')),
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message should not be blank.')),
                new Length(array('min' => 5)),
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}
