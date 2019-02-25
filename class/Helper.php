<?php namespace Samuell\DomainLocale\Classes;

use Request;

class Helper
{
    static public function getUserLocale(): string
    {
        $locales = Locale::listAvailable();
        foreach (Request::getLanguages() as $requestLang) {
            if (array_key_exists($requestLang, $locales)) {
                return $requestLang;
            }
        }
    }

    static public function getDomainTld($host): string
    {
        return str_replace('.', '', substr(strrchr($host, '.'), 0));
    }
}
