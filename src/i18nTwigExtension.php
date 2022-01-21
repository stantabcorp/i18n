<?php

namespace i18n;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class i18nTwigExtension extends AbstractExtension
{

    private $i18n;

    public function __construct(i18n $i18n)
    {
        $this->i18n = $i18n;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('i18n', [$this, 'i18n']),
        ];
    }

    /**
     * @inheritDoc \i18n\i18n::get()
     */
    public function i18n($word)
    {
        try {
            return $this->i18n->get($word);
        } catch (\Exception $e) {
            return "i18n.error ($word)";
        }
    }
}
