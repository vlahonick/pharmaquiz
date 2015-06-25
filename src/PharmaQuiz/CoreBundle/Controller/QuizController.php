<?php

namespace PharmaQuiz\CoreBundle\Controller;

use PharmaQuiz\CoreBundle\Quizzer;
use PharmaQuiz\CoreBundle\Entity\QuizAnswer;
use PharmaQuiz\CoreBundle\Entity\QuizCategory;
use PharmaQuiz\CoreBundle\Entity\QuizQuestion;
use PharmaQuiz\CoreBundle\Form\Type\QuizQuestionType;
use PharmaQuiz\CoreBundle\Form\Type\QuizzerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuizController extends Controller {

    public function quizAction()
    {
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $params = array(
            'title' => $translator->trans('Quiz'),
            'subtitle' => $translator->trans('Choose a quiz'),
            'quote' => $this->get('quote_factory')->getRandom(),
            'categories' => $em->getRepository('PharmaQuizCoreBundle:QuizCategory')->findAll(),
        );
        return $this->render('PharmaQuizCoreBundle:Default:quiz_selector.html.twig', $params);
    }

    public function quizAllAction(Request $request)
    {
        $limit = (int) $request->query->get('questions');
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('PharmaQuizCoreBundle:QuizQuestion')->findRandom($limit);
        $subtitle = $this->get('translator')
            ->trans('Topic: All topics | Questions: %questions%', array('%questions%' => $limit));
        $request->getSession()->set('retry_path', $this->generateUrl('_quiz_all') . '?questions=' . $limit);
        return $this->renderQuizForm($request, $questions, $subtitle, $limit);
    }

    public function quizCategoryAction(Request $request, QuizCategory $category)
    {
        $limit = (int) $request->query->get('questions');
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('PharmaQuizCoreBundle:QuizQuestion')->findRandomByCategory($category->getId(), $limit);
        $subtitle = $this->get('translator')
            ->trans('Topic: %category% | Questions: %questions%', array(
                '%category%' => $category->getTitle(),
                '%questions%' => $limit,
            ));
        $request->getSession()->set('retry_path', $this->generateUrl('_quiz_category', array('category' => $category->getId())) . '?questions=' . $limit);
        return $this->renderQuizForm($request, $questions, $subtitle, $limit);
    }

    public function quizResultsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $request->getSession()->get('quiz_results');
        if (!$results) {
          return $this->redirect($this->generateUrl('_quiz'));
        }
        $quizzer = new Quizzer();
        foreach ($results as $name => $value) {
            $question_id = str_replace('question', '', $name);
            if (strpos($name, 'question') !== 0 || !is_numeric($question_id)) {
              continue;
            }

            $question = $em->getRepository('PharmaQuizCoreBundle:QuizQuestion')->find($question_id);
            $value = (array) $value;
            $answers = array();
            foreach ($value as $answer) {
                $answers[$answer] = $em->getRepository('PharmaQuizCoreBundle:QuizAnswer')->find($answer);
            }
            $quizzer->addAnsweredQuestion($question, $answers);
        }

        $translator = $this->get('translator');
        $params = array(
            'title' => $translator->trans('Quiz results'),
            'quote' => $this->get('quote_factory')->getRandom(),
            'final_score' => $quizzer->getFinalScore(),
            'total_correct' => $quizzer->getNumberOfCorrectQuestions(),
            'total_wrong' => $quizzer->getNumberOfWrongQuestions(),
            'number_of_questions' => $quizzer->getNumberOfQuestions(),
            'corrections' => $quizzer->getCorrections(),
            'retry_path' => $request->getSession()->get('retry_path'),
        );
        return $this->render('PharmaQuizCoreBundle:Default:quiz_results.html.twig', $params);
    }

    protected function renderQuizForm(Request $request, array $questions, $subtitle, $limit)
    {
        $session = $request->getSession();
        if ($request->request->get('quiz_questions') && $session->get('quiz_questions')) {
            // CSRF protection is annoying because of the random choice generation.
            $posted_questions = array();
            $repo = $this->getDoctrine()->getManager()->getRepository('PharmaQuizCoreBundle:QuizQuestion');
            foreach ($session->get('quiz_questions') as $id) {
                if ($question = $repo->find($id)) {
                    $posted_questions[] = $question;
                }
            }
            if ($posted_questions && count($posted_questions) == $limit) {
                $questions = $posted_questions;
            }
        }
        else {
            $ids = array();
            foreach ($questions as $question) {
              $ids[] = $question->getId();
            }
            $session->set('quiz_questions', $ids);
        }

        $translator = $this->get('translator');
        $form = $this->createForm(new QuizzerType($questions));
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $session->remove('quiz_questions');

                foreach ($form->all() as $key => $value) {
                    $results[$key] = $form->get($key)->getData();
                }
                $session->set('quiz_results', $results);

                return $this->redirect($this->generateUrl('_quiz_results'));
            }
        }

        $params = array(
            'title' => $translator->trans('Quiz'),
            'subtitle' => $subtitle,
            'quote' => $this->get('quote_factory')->getRandom(),
            'form' => $form->createView(),
        );
        return $this->render('PharmaQuizCoreBundle:Form:quizzer.html.twig', $params);
    }


    public function addQuestionAction($_locale, Request $request)
    {
        $translator = $this->get('translator');

        $question = new QuizQuestion();
        $question->setPublished(0);
        $question->setCurrentLocale($_locale);
        for ($i = 0; $i < 5; $i++) {
            $answer = new QuizAnswer();
            $answer->setCurrentLocale($_locale);
            $answer->setQuestion($question);
            $question->addAnswer($answer);
        }

        $form = $this->createForm(new QuizQuestionType(), $question);
        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                if (!$question->getTitle()) {
                    $request->getSession()->getFlashBag()->add('error', $translator->trans('Please add a Question title.'));
                    return $this->redirect($this->generateUrl('_quiz_question_add'));
                }

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($question);
                // In order to persist new translations, call mergeNewTranslations method, before flush
                $question->mergeNewTranslations();

                foreach($question->getAnswers() as $answer) {
                    if (!$answer->getAnswer()) {
                        $request->getSession()->getFlashBag()->add('error', $translator->trans('Please fill all the answers.'));
                        return $this->redirect($this->generateUrl('_quiz_question_add'));
                    }
                    $em->persist($answer);
                    $answer->mergeNewTranslations();
                }

                $em->flush();
                $request->getSession()->getFlashBag()->add('success', $translator->trans('Your question has been added.'));

                return $this->redirect($this->generateUrl('_quiz_question_add'));
            }
        }
        $params = array(
            'title' => $translator->trans('Add question'),
            'quote' => $this->get('quote_factory')->getRandom(),
            'form' => $form->createView(),
        );
        return $this->render('PharmaQuizCoreBundle:Form:quiz_question.html.twig', $params);
    }

}
