<?php

namespace PharmaQuiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Page
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="PharmaQuiz\CoreBundle\Entity\PageRepository")
 */
class Page
{
    use ORMBehaviors\Translatable\Translatable,
        ORMBehaviors\Sluggable\Sluggable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * {@inheritDoc}
     */
    public function getSluggableFields()
    {
        return array('title');
    }

    /**
     * {@inheritDoc}
     */
    private function getRegenerateSlugOnUpdate()
    {
        return false;
    }

    /**
     * Proxy method calls to the translation class.
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
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
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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

}
