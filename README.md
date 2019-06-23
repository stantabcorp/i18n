# i18n
Internationalization library for PHP
This library allows you to translate your website with ini files, Auto Detecting the language and much more!

<p align="center">
<a href="https://packagist.org/packages/stantabcorp/i18n"><img src="https://poser.pugx.org/stantabcorp/i18n/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/stantabcorp/i18n"><img src="https://poser.pugx.org/stantabcorp/i18n/downloads" alt="Total Downloads"></a>
<a href="https://discord.gg/hQnY3jP"><img src="https://discordapp.com/api/guilds/365929044442873898/widget.png" alt="Discord Server"></a>
</p>


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
4. An array of options

**Example:**
Without AutoDetect: 
```
$i18n = new i18n("en", "en", ["en", "fr"])
```
This will set the language to `en` (English), set the default language to `en` (English) and set that `en` (English) and `fr` (French) are available

With AutoDetect: 
```
$i18n = new i18n(true, "en", ["en", "fr"])
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

Want to change the translation folder on the fly?
```
$i18n->setFolder("path/to/the/new/folder");
```

Want to get the active folder?
```
$i18n->getFolder();
```

Want to set the available languages?
```
$i18n->setAvailableLanguages(array);
```

Want to get the available languages?
```
$i18n->getAvailableLanguage();
```

Now, let set and get the default language
```
$i18n->setDefaultLanguage("string");
$i18n->getDefaultLanguage();
```

### File syntax

**Sections are supported by i18n, see options to enable it**

*Example file:*

```
word1 = "Some word"
word2 = "Some other word"
```

### Options

The fourth parameter when initializing the i18n class is an array.  
Accepted values are:  
* `error`, a boolean to enable or disable error throwing
* `sections`, a boolean to enable or not sections in the ini files
* `path`, to set a path for the translations