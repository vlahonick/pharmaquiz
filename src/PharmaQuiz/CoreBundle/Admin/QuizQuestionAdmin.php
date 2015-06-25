<?php

namespace PharmaQuiz\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class QuizQuestionAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('published', 'checkbox', array('required' => false))
            ->add('category')
            ->add('translations', 'a2lix_translations')
            ->add('answers', 'sonata_type_collection', array(
                'by_reference' => false
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category');
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('translations');
    }

    public function prePersist($question)
    {
        foreach ($question->getAnswers() as $answer) {
            $answer->setQuestion($question);
        }
    }

    public function preUpdate($question)
    {
        foreach ($question->getAnswers() as $answer) {
            $answer->setQuestion($question);
        }
    }

}
