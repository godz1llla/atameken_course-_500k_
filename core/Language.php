<?php

class Language {
    private static $currentLang = 'kk';
    private static $translations = [];
    
    public static function init() {
        if (isset($_GET['lang'])) {
            self::setLanguage($_GET['lang']);
        } elseif (Session::has('language')) {
            self::$currentLang = Session::get('language');
        }
        
        self::loadTranslations();
    }
    
    public static function setLanguage($lang) {
        if (in_array($lang, ['kk', 'ru', 'en'])) {
            self::$currentLang = $lang;
            Session::set('language', $lang);
            self::loadTranslations();
        }
    }
    
    public static function getCurrentLanguage() {
        return self::$currentLang;
    }
    
    private static function loadTranslations() {
        $langFile = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }
    
    public static function get($key, $default = null) {
        $keys = explode('.', $key);
        $value = self::$translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default ?? $key;
            }
        }
        
        return $value;
    }
    
    public static function getLanguages() {
        return [
            'kk' => 'Қазақша',
            'ru' => 'Русский',
            'en' => 'English'
        ];
    }
}

function __($key, $default = null) {
    return Language::get($key, $default);
}

