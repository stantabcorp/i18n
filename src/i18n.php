<?php

    /**
    * i18n
    *
    * Internationalization library for PHP 
    * @author Thibault JUNIN <spamfree@thibaultjunin.fr>
    * @copyright STAN-TAb Corp. 2017 - 2018
    * @link https://github.com/stantabcorp/i18n
    * @license proprietary
    */

    namespace i18n;

    class i18n{

        private $lang;
        private $default;
        private $allow;
        private $folder = "./i18n/";

        public function __construct($lang, $default, array $allow){
            $this->default = $default;
            $this->lang = ($lang === true) ? $this->autoDetect($allow) : $lang;
            $this->allow = $allow;

            if(!file_exists($this->folder)){
                mkdir($this->folder);
            }

            if(!file_exists($this->folder . $this->lang . ".ini")){
                file_put_contents($this->folder . $this->lang . ".ini", "; i18n file \n");
            }

            if(!file_exists($this->folder . $this->default . ".ini")){
                throw new Exception("Default language not found !");
            }
        }

        private function getLangArray(){
            return parse_ini_file($this->folder . $this->lang . ".ini");
        }

        private function getDefaultArray(){
            return parse_ini_file($this->folder . $this->default . ".ini");
        }

        public function get($word){
            $lang = $this->getLangArray();
            $default = $this->getDefaultArray();
            if(isset($lang[$word])){
                return $lang[$word];
            }elseif(isset($default[$word])){
                return $default[$word];
            }else{
                throw new \Exception("Word $word not found !");
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
		    $language = $language{0}.$language{1};
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
