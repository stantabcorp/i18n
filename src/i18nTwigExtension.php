<?php 

    namespace i18n;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFilter;

    class i18nTwigExtension extends AbstractExtension
    {

        private $i18n;

        public function __construct(i18n $i18n){
            $this->i18n = $i18n;
        }

        public function getFilters()
        {
            return array(
                new TwigFilter('i18n', array($this, 'i18nFilter')),
            );
        }

        public function i18nFilter($word)
        {
            try{
                $wo = $this->i18n->get($word);
                return $wo;
            }catch(\Exception $e){
                return "i18n.error";
            }
        }
    }