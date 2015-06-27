<?php

namespace PharmaQuiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * QuizQuestion
 *
 * @ORM\Table(name="quiz_questions")
 * @ORM\Entity(repositoryClass="PharmaQuiz\CoreBundle\Entity\QuizQuestionRepository")
 */
class QuizQuestion
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
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @ORM\OneToMany(targetEntity="QuizAnswer", mappedBy="question", cascade={"all"})
     */
    protected $answers;

    /**
     * @ORM\ManyToOne(targetEntity="QuizCategory", inversedBy="questions")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $category;

    /**
     * Proxy method calls to the translation class.
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * Show the title of the translation using the __call proxy.
     *
     * Sta questions anti gia
     * 'Item "PharmaQuiz\CoreBundle\Entity\Page:000000000b170ead00007f6ae9dc88fb" has been successfully updated.'
     * emfanizei
     * 'Item "Terms of use" has been successfully updated.'
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
        $this->answers = new ArrayCollection();
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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return QuizQuestion
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Add answers
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizAnswer $answers
     * @return QuizQuestion
     */
    public function addAnswer(\PharmaQuiz\CoreBundle\Entity\QuizAnswer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizAnswer $answers
     */
    public function removeAnswer(\PharmaQuiz\CoreBundle\Entity\QuizAnswer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set category
     *
     * @param \PharmaQuiz\CoreBundle\Entity\QuizCategory $category
     * @return QuizQuestion
     */
    public function setCategory(\PharmaQuiz\CoreBundle\Entity\QuizCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \PharmaQuiz\CoreBundle\Entity\QuizCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Retrieve only the correct answers.
     *
     * @return \PharmaQuiz\CoreBundle\Entity\QuizAnswer[]
     */
    public function getCorrectAnswers()
    {
        $correct = array();
        foreach ($this->getAnswers() as $answer) {
            if ($answer->getIsCorrect()) {
                $correct[$answer->getId()] = $answer;
            }
        }

        return $correct;
    }

    /**
     * Retrieve the number of the correct answers.
     *
     * @return int
     */
    public function getNumberOfCorrectAnswers()
    {
        return count($this->getCorrectAnswers());
    }


    /**
     * Set published
     *
     * @param boolean $published
     * @return QuizQuestion
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
}
