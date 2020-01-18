<?php 

    namespace i18n;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFilter;

    class i18nTwigExtension extends \Twig\Extension\AbstractExtension
    {

        private $i18n;

        public function __construct(i18n $i18n){
            $this->i18n = $i18n;
        }

        public function getFunctions()
        {
            return array(
                new \Twig\TwigFunction('i18n', [$this, 'i18n']),
            );
        }


        public function i18n($word)
        {
            try{
                $wo = $this->i18n->get($word);
                return $wo;
            }catch(\Exception $e){
                return "i18n.error ($word)";
            }
        }
    }
