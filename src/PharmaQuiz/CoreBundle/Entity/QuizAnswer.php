<?php

namespace PharmaQuiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * QuizAnswer
 *
 * @ORM\Table(name="quiz_answers")
 * @ORM\Entity(repositoryClass="PharmaQuiz\CoreBundle\Entity\QuizAnswerRepository")
 */
class QuizAnswer
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="question_id", type="integer")
     */
    private $questionId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_correct", type="boolean")
     */
    private $isCorrect;

    /**
     * @ORM\ManyToOne(targetEntity="QuizQuestion", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;


    /**
     * Proxy method calls to the translation class.
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * Show the answer of the translation using the __call proxy.
     */
    public function __toString()
    {
        return $this->getAnswer();
    }

    /**
     * Proxy getter calls to the translation class.
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        return $this->proxyCurrentLocaleTranslation($method);
    }

    /**
     * Proxy setter calls to the translation class.
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        return $this->proxyCurrentLocaleTranslation($method, array($value));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set questionId
     *
     * @param integer $questionId
     * @return QuizAnswer
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return integer
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set isCorrect
     *
     * @param boolean $isCorrect
     * @return QuizAnswer
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * Get isCorrect
     *
     * @return boolean
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * Set question
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizQuestion $question
     * @return QuizAnswer
     */
    public function setQuestion(\PharmaQuiz\CoreBundle\Entity\QuizQuestion $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \PharmaQuiz\CoreBundle\Entity\QuizQuestion
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
