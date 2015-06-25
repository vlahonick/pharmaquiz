<?php

namespace PharmaQuiz\CoreBundle;

class QuoteFactory {

    protected $translator;

    public function __construct($translator) {
        $this->translator = $translator;
    }

    public function getRandom() {
        $translator = $this->translator;
        $quotes = array(
            (object) array(
                'text' => $translator->trans('Let food be thy medicine and medicine be thy food.'),
                'author' => $translator->trans('Hippocrates'),
            ),
            (object) array(
                'text' => $translator->trans('Make a habit of two things, to help or at least to do no harm.'),
                'author' => $translator->trans('Hippocrates'),
            ),
            (object) array(
                'text' => $translator->trans('Everything in excess is opposed to nature.'),
                'author' => $translator->trans('Hippocrates'),
            ),
            (object) array(
                'text' => $translator->trans('Wherever the art of medicine is loved, there is also a love of humanity.'),
                'author' => $translator->trans('Hippocrates'),
            ),
            (object) array(
                'text' => $translator->trans('Walking is mans best medicine.'),
                'author' => $translator->trans('Hippocrates'),
            ),
        );
        $key = array_rand($quotes);
        return $quotes[$key];
    }

}
