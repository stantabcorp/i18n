# i18n
Internationalization library for PHP
This library allows you to translate your website with ini files, Auto Detecting the language and much more!

## Documentation

### Information

i18n will create a folder named "i18n" please make sur it is readable 

### Installation

1. Via composer: `composer require stantabcorp/i18n`
2. Via manually download and install

### Initialization

In the i18n constructor your must have **3** parameters:
1. The language you want to display your website **or** true to use AutoDetect
2. The default language
3. An array of available languages

**Example:**
Without AutoDetect: 
```
$i18n = new i18n("en", "en", ["en","fr"])
```
This will set the language to `en` (English), set the default language to `en` (English) and set that `en` (English) and `fr` (French) are available

With AutoDetect: 
```
$i18n = new i18n(true, "en", ["en","fr"])
```
This will set the language to AutoDetect, set the default language and set available language for AutoDetect

### Usage

In order to get a translation you just need to:
```
$i18n->get("index in your file");
```

If you want to get the current language:
```
$i18n->getLang();
```

You want to change the language?
```
$i18n->setLang("language");
```

You don't want to use `sprintf`?! No problem:
```
$i18n->replace("string", [
    "string" => "strong"
]);
```

### File syntax

**Sections are not yet supported by i18n**

*Example file:*

```
word1 = "Some word"
word2 = "Some other word"
```
