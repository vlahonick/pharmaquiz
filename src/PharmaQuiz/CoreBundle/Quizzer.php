<?php

namespace PharmaQuiz\CoreBundle;

use PharmaQuiz\CoreBundle\Entity\QuizQuestion;

class Quizzer {

    protected $questions = array();

    protected $correctQuestions = array();
    protected $wrongQuestions = array();

    protected $correctAnswers = array();
    protected $wrongAnswers = array();

    protected $index = 0;

    protected $currentScore = 0;
    protected $finalScore = 0;

    /**
     *
     * @param array $given_answers
     *   An array keyed by answer id.
     */
    public function addAnsweredQuestion(QuizQuestion $question, array $given_answers)
    {
        $question_id = $question->getId();
        $this->questions[$question_id] = $question;
        // @todo Perform validation.
        //$question_answers = $question->getAnswers();

        $given_answers_correct = $given_answers_wrong = $correct_answers_unselected = 0;
        $question_is_correct = TRUE;
        // Keep track of the order the user submitted the questions.
        $this->index++;

        foreach ($question->getAnswers() as $answer) {
            if ($answer->getIsCorrect()) {
                if (array_key_exists($answer->getId(), $given_answers)) {
                    $given_answers_correct++;
                }
                else {
                    $correct_answers_unselected++;
                    $question_is_correct = FALSE;
                    // We will check later for the below.
                    // $given_answers_wrong++;
                }
            }
            else {
                if (array_key_exists($answer->getId(), $given_answers)) {
                    $given_answers_wrong++;
                    $question_is_correct = FALSE;
                }
            }
        }

        if ($question_is_correct) {
            $this->correctQuestions[$question_id] = $question;
            $this->correctAnswers[$question_id][$this->index] = $given_answers;
        }
        else {
            $this->wrongQuestions[$question_id] = $question;
            $this->wrongAnswers[$question_id][$this->index] = $given_answers;
        }

        if ($correct_answers_unselected && !$given_answers_wrong) {
            // If we have unselected answers that are correct then count them
            // in the wrong answers stack.
            $given_answers_wrong += $correct_answers_unselected;
        }

        $this->currentScore += ((100/$question->getNumberOfCorrectAnswers())*$given_answers_correct)-(10*$given_answers_wrong);
    }

    /**
     * @return float
     */
    public function getCurrentScore()
    {
        return $this->currentScore;
    }

    /**
     * @return float
     */
    public function getFinalScore()
    {
        $number_of_questions = $this->getNumberOfQuestions();
        return $this->currentScore/$number_of_questions > 0 ? $this->currentScore/$number_of_questions : 0;
    }

    /**
     * @return int
     */
    public function getNumberOfCorrectQuestions()
    {
        return count($this->correctQuestions);
    }

    /**
     * @return int
     */
    public function getNumberOfWrongQuestions()
    {
        return count($this->wrongQuestions);
    }

    /**
     * @return int
     */
    public function getNumberOfQuestions()
    {
        return $this->getNumberOfCorrectQuestions() + $this->getNumberOfWrongQuestions();
    }

    /**
     * @return array
     */
    public function getCorrections()
    {
        $corrections = array();
        foreach ($this->wrongAnswers as $question_id => $wrong) {

            $question = $this->questions[$question_id];
            foreach ($wrong as $index => $given_answers) {
                $corrections[$index] = array(
                    'title' => $question->getTitle(),
                    // array_reverse is needed for table (it also resets keys)
                    'wrong' => array_reverse($given_answers),
                    'correct' => array_reverse($question->getCorrectAnswers()),
                );
            }
        }
        return $corrections;
    }
}
