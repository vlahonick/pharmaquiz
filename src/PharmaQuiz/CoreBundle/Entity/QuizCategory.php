<?php

namespace PharmaQuiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * QuizCategory
 *
 * @ORM\Table("quiz_categories")
 * @ORM\Entity(repositoryClass="PharmaQuiz\CoreBundle\Entity\QuizCategoryRepository")
 */
class QuizCategory
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
     * @ORM\OneToMany(targetEntity="QuizQuestion", mappedBy="category")
     */
    protected $questions;

    /**
     * Proxy method calls to the translation class.
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
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
     * Show the title of the translation using the __call proxy.
     */
    public function __toString()
    {
        return $this->getTitle();
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * Add questions
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizQuestion $questions
     * @return QuizCategory
     */
    public function addQuestion(\PharmaQuiz\CoreBundle\Entity\QuizQuestion $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizQuestion $questions
     */
    public function removeQuestion(\PharmaQuiz\CoreBundle\Entity\QuizQuestion $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
