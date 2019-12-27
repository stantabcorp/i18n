<?php

    /**
    * i18n
    *
    * Internationalization library for PHP 
    * @author Thibault JUNIN <spamfree@thibaultjunin.fr>
    * @copyright STAN-TAb Corp. 2017 - 2019
    * @link https://github.com/stantabcorp/i18n
    * @license proprietary
    */

    namespace i18n;

    class i18n{

        private $lang;
        private $default;
        private $allow;
        private $folder = "./i18n/";
        private $error;
        private $sections;

        public function __construct($lang, $default, array $allow, array $options = []){
            $this->default = $default;
            $this->lang = ($lang === true) ? $this->autoDetect($allow) : $lang;
            $this->allow = $allow;
            $this->error = isset($options['error']) ? $options['error'] : true;
            $this->sections = isset($options['sections']) ? $options['sections'] : false;
            if(isset($options['path'])){
                $this->folder = $options['path'];
            }

            if(!file_exists($this->folder)){
                mkdir($this->folder);
            }

            if(!file_exists($this->folder . $this->lang . ".ini")){
                file_put_contents($this->folder . $this->lang . ".ini", "; i18n file \n");
            }

            if(!file_exists($this->folder . $this->default . ".ini")){
                if($this->error){
                    throw new \Exception("Default language not found !");
                }
            }
        }

        private function getLangArray(){
            return parse_ini_file($this->folder . $this->lang . ".ini", $this->sections);
        }

        private function getDefaultArray(){
            return parse_ini_file($this->folder . $this->default . ".ini", $this->sections);
        }

        public function get($word){
            if($this->sections){
                if(strpos($word, "[") && strpos($word, "]")){
                    $w = explode("[", $word);
                    return $this->getWordWithSection($w[0], rtrim($w[1], "]"));
                }
            }
            return $this->getWordWithoutSection($word);
        }

        private function getWordWithoutSection($word){
            $lang = $this->getLangArray();
            $default = $this->getDefaultArray();
            if(isset($lang[$word])){
                return $lang[$word];
            }elseif(isset($default[$word])){
                return $default[$word];
            }else{
                if($this->error){
                    throw new \Exception("Word $word not found !");
                }else{
                    return "i18n.error ($word)";
                }
            }
        }

        private function getWordWithSection($section, $word){
            $lang = $this->getLangArray();
            $default = $this->getDefaultArray();
            if(isset($lang[$section][$word])){
                return $lang[$section][$word];
            }elseif(isset($default[$section][$word])){
                return $default[$section][$word];
            }else{
                if($this->error){
                    throw new \Exception("Word {$section}.{$word} not found !");
                }else{
                    return "i18n.error ({$section}.{$word})";
                }
            }
        }

        private function autoDetect($allowed){
            if($allowed == null){
                return $this->default;
            }
            if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                return $this->default;
            }
            $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		    $language = $language[0].$language[1];
            if(in_array($language, $allowed)){
                return $language;
            }
            return $this->default;
        }

        public function getLang(){
            return $this->lang;
        }

        public function replace($str, array $data){
            foreach($data as $key => $value){
                $str = str_replace($key, $value, $str);
            }
            return $str;
        }

        public function setLang($lang){
            if($lang === true){
                $this->lang = $this->autoDetect($this->allow);
            }else{
                if(in_array($lang, $this->allow)){
                    $this->lang = $lang;
                }else{
                    $this->lang = $this->default;
                }
            }
        }

        public function setFolder($folder){
            $this->folder = $folder;
        }

        public function getFolder(){
            return $this->folder;
        }

        public function setAvailableLanguages(array $languages){
            $this->allow = $languages;
        }
        
        public function getAvailableLanguages(){
            return $this->allow;
        }

        public function setDefaultLanguage($default){
            $this->default = $default;
        }
        
        public function getDefaultLanguage(){
            return $this->default;
        }

    }
