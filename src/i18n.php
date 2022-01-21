<?php

/**
 * i18n
 *
 * Internationalization library for PHP
 *
 * @author    Thibault JUNIN <spamfree@thibaultjunin.fr>
 * @copyright STAN-TAb Corp. 2017 - 2022
 * @link      https://github.com/stantabcorp/i18n
 * @license   see LICENCE.md
 */

namespace i18n;

use Adbar\Dot;
use i18n\Exceptions\DefaultLanguageNotFoundException;
use i18n\Exceptions\TranslationNotFoundException;
use Locale;

/**
 * @covers \i18n\i18n
 */
class i18n
{

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $default;

    /**
     * @var array
     */
    private $allowed;

    /**
     * @var string
     */
    private $folder = "./i18n/";

    /**
     * @var bool
     */
    private $error = true;

    /**
     * @var bool
     */
    private $sections = false;

    /**
     * @param  $lang
     * @param  $default
     * @param array $allowed
     * @param array $options
     * @throws DefaultLanguageNotFoundException
     */
    public function __construct($lang, $default, array $allowed, array $options = [])
    {
        $this->default = $default;
        $this->lang = ($lang === true) ? $this->autoDetect($allowed) : $lang;
        $this->allowed = $allowed;

        // Options
        $this->error = $options['error'] ?? true;
        $this->sections = $options['sections'] ?? false;
        if (isset($options['path'])) {
            $this->folder = $options['path'];
        }

        if (!file_exists($this->folder)) {
            mkdir($this->folder);
        }

        $langFile = sprintf("%s/%s.ini", $this->folder, $this->lang);
        $defaultLangFile = sprintf("%s/%s.ini", $this->folder, $this->default);

        if (!file_exists($langFile)) {
            file_put_contents($langFile, "; i18n file \n");
        }

        if (!file_exists($defaultLangFile)) {
            if ($this->error) {
                throw new DefaultLanguageNotFoundException("Default language not found !");
            }
        }
    }

    /**
     * @return array|false
     */
    private function getLangArray()
    {
        return parse_ini_file(sprintf("%s/%s.ini", $this->folder, $this->lang), $this->sections);
    }

    /**
     * @return array|false
     */
    private function getDefaultArray()
    {
        return parse_ini_file(sprintf("%s/%s.ini", $this->folder, $this->default), $this->sections);
    }

    /**
     * @param  $word
     * @return mixed|string
     * @throws TranslationNotFoundException
     */
    public function get($word)
    {

        if ($this->sections) {
            if (strpos($word, "[") && strpos($word, "]")) {
                $w = explode("[", $word);
                $word = sprintf("%s.%s", $w[0], rtrim($w[1], "]"));
            }
        }

        $dot = new Dot($this->getLangArray());
        if ($dot->has($word)) {
            return $dot->get($word);
        } else {
            $default = new Dot($this->getDefaultArray());
            if ($default->has($word)) {
                return $default->get($word);
            }
        }

        if ($this->error) {
            throw new TranslationNotFoundException("Word $word not found !");
        } else {
            return "i18n.error ($word)";
        }

    }

    /**
     * @param  $locales
     * @return mixed|string
     */
    private function autoDetect($locales)
    {
        // No locales return the default one
        if ($locales == null) {
            return $this->default;
        }

        // No Accept Language Header, return the default language
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return $this->default;
        }

        // Get the language from the http headers
        $mine = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        // Try to match to the best available locale
        $result = Locale::lookup($locales, $mine);
        if (empty($result)) {
            // There is no local matching...

            $copy = array_map(
                function ($elt) {
                    // Convert locale to their primary form (en_GB to en)
                    return Locale::getPrimaryLanguage($elt);
                }, $locales
            );

            // Try to match again
            $result = Locale::lookup($copy, $mine);

            if (empty($result)) {
                // Nothing found, return the default one
                return $this->default;
            }

            // Convert to the original form
            $result = $locales[array_keys($copy, $result)[0]];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowed(): array
    {
        return $this->allowed;
    }

    /**
     * @param array $allowed
     */
    public function setAllowed(array $allowed)
    {
        $this->allowed = $allowed;
    }

    /**
     * @return mixed|string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param      $str
     * @param array $data
     * @return     array|mixed|string|string[]
     * @deprecated
     */
    public function replace($str, array $data)
    {
        foreach ($data as $key => $value) {
            $str = str_replace($key, $value, $str);
        }
        return $str;
    }

    /**
     * @param $lang
     */
    public function setLang($lang)
    {
        if ($lang === true) {
            $this->lang = $this->autoDetect($this->allowed);
        } else {
            if (in_array($lang, $this->allowed)) {
                $this->lang = $lang;
            } else {
                $this->lang = $this->default;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return mixed|string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed|string $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return bool|mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param bool|mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return false|mixed
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param false|mixed $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }

}
