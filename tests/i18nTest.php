<?php


use i18n\Exceptions\DefaultLanguageNotFoundException;
use i18n\Exceptions\TranslationNotFoundException;
use i18n\i18n;
use PHPUnit\Framework\TestCase;

class i18nTest extends TestCase
{

    public function __construct()
    {
        file_put_contents(sprintf("%s/../i18n/en.ini", __DIR__), <<<EOF
test = "test"
fallback = "fallback"
[section]
section_test = "test section"
section_fallback = "section fallback"
EOF
        );
        file_put_contents(sprintf("%s/../i18n/fr.ini", __DIR__), <<<EOF
test = "test"
[section]
section_test = "test section"
EOF
        );
        parent::__construct();
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSimpleGet()
    {
        $i18n = new i18n("en", "en", ["en"]);
        $this->assertEquals("test", $i18n->get("test"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionGet()
    {
        $i18n = new i18n("en", "en", ["en"], [
            "sections" => true
        ]);
        $this->assertEquals("test section", $i18n->get("section.section_test"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionGetBrackets()
    {
        $i18n = new i18n("en", "en", ["en"], [
            "sections" => true
        ]);
        $this->assertEquals("test section", $i18n->get("section[section_test]"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSimpleNonExisting()
    {
        $i18n = new i18n("en", "en", ["en"], [
            "error" => false
        ]);
        $this->assertEquals("i18n.error (non_existing)", $i18n->get("non_existing"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionGetNonExisting()
    {
        $i18n = new i18n("en", "en", ["en"], [
            "sections" => true,
            "error" => false
        ]);
        $this->assertEquals("i18n.error (section.non_existing)", $i18n->get("section.non_existing"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionGetBracketsNonExisting()
    {
        $i18n = new i18n("en", "en", ["en"], [
            "sections" => true,
            "error" => false
        ]);
        $this->assertEquals("i18n.error (section.non_existing)", $i18n->get("section[non_existing]"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSimpleFallbackGet()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"]);
        $this->assertEquals("fallback", $i18n->get("fallback"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionFallbackGet()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"], [
            "sections" => true
        ]);
        $this->assertEquals("section fallback", $i18n->get("section.section_fallback"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionFallbackGetBrackets()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"], [
            "sections" => true
        ]);
        $this->assertEquals("section fallback", $i18n->get("section[section_fallback]"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSimpleFallbackNonExisting()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"], [
            "error" => false
        ]);
        $this->assertEquals("i18n.error (fallback_ne)", $i18n->get("fallback_ne"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionFallbackGetNonExisting()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"], [
            "sections" => true,
            "error" => false
        ]);
        $this->assertEquals("i18n.error (section.fallback_ne)", $i18n->get("section.fallback_ne"));
    }

    /**
     * @throws DefaultLanguageNotFoundException
     * @throws TranslationNotFoundException
     * @covers \i18n\i18n::get
     * @covers \i18n\i18n::__construct
     */
    public function testSectionFallbackGetBracketsNonExisting()
    {
        $i18n = new i18n("fr", "en", ["en", "fr"], [
            "sections" => true,
            "error" => false
        ]);
        $this->assertEquals("i18n.error (section.fallback_ne)", $i18n->get("section[fallback_ne]"));
    }

}
