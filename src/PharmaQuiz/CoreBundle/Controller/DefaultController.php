<?php

namespace PharmaQuiz\CoreBundle\Controller;

use PharmaQuiz\CoreBundle\Entity\Page;
use PharmaQuiz\CoreBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $homepage = $em->getRepository('PharmaQuizCoreBundle:Page')->findBySlug('home');
        $homepage = reset($homepage);
        $params = array(
            'title' => $homepage->getTitle(),
            'quote' => $this->get('quote_factory')->getRandom(),
            'content' => $homepage->getBody(),
        );
        return $this->render('PharmaQuizCoreBundle:Default:home.html.twig', $params);
    }

    public function pageAction($_locale, Page $page)
    {
        $params = array(
            'title' => $page->getTitle(),
            'quote' => $this->get('quote_factory')->getRandom(),
            'content' => $page->getBody(),
        );
        return $this->render('PharmaQuizCoreBundle:Default:page.html.twig', $params);
    }

    public function contactAction(Request $request)
    {
        $translator = $this->get('translator');
        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo('vlahonick@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'PharmaQuizCoreBundle:Mail:contact.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('name')->getData(),
                                'subject' => $form->get('subject')->getData(),
                                'message' => $form->get('message')->getData(),
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', $translator->trans('Your message has been sent. Thank you!'));

                return $this->redirect($this->generateUrl('_contact'));
            }
        }

        $params = array(
            'title' => $translator->trans('Contact'),
            'quote' => $this->get('quote_factory')->getRandom(),
            'form' => $form->createView(),
        );
        return $this->render('PharmaQuizCoreBundle:Form:contact.html.twig', $params);
    }
}
