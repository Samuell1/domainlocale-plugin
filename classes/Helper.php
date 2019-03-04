<?php namespace Samuell\DomainLocale\Classes;

use Request;

use RainLab\Translate\Models\Locale;

class Helper
{
    static public function getUserLocale(): ?string
    {
        $locales = Locale::listAvailable();
        foreach (Request::getLanguages() as $requestLang) {
            if (array_key_exists($requestLang, $locales)) {
                return $requestLang;
            }
        }
        return null;
    }

    static public function getDomainTld($host): string
    {
        return str_replace('.', '', substr(strrchr($host, '.'), 0));
    }
}
