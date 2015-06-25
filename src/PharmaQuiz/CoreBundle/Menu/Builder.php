<?php

namespace PharmaQuiz\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        // adds the menu class to nav ul in /app/Resources/views/base.html.twig
        $menu->setChildrenAttribute('class', 'menu');

        $translator = $this->container->get('translator');

        $menu->addChild($translator->trans('Home'), array(
            'route' => '_home',
        ));
        $menu->addChild($translator->trans('Quiz'), array(
            'route' => '_quiz',
        ));
        $menu->addChild($translator->trans('FAQ'), array(
            'route' => '_page',
            'routeParameters' => array('slug' => 'faq'),
        ));
        $menu->addChild($translator->trans('Contact'), array(
            'route' => '_contact',
        ));

        if (strpos($this->container->get('request')->getRequestUri(), '/quiz/')) {
            $href = explode('/quiz/', $this->container->get('request')->getRequestUri());
            $menu->setCurrentUri($href[0] . '/quiz');
        }
        else {
          $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        }

        return $menu;
    }

    public function footerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $translator = $this->container->get('translator');

        $menu->addChild($translator->trans('Home'), array(
            'route' => '_home',
        ));
        $menu->addChild($translator->trans('Quiz'), array(
            'route' => '_quiz',
        ));
        $menu->addChild($translator->trans('FAQ'), array(
            'route' => '_page',
            'routeParameters' => array('slug' => 'faq'),
        ));
        $menu->addChild($translator->trans('Contact'), array(
            'route' => '_contact',
        ));

        if (strpos($this->container->get('request')->getRequestUri(), '/quiz/')) {
            $href = explode('/quiz/', $this->container->get('request')->getRequestUri());
            $menu->setCurrentUri($href[0] . '/quiz');
        }
        else {
          $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        }

        return $menu;
    }
}
