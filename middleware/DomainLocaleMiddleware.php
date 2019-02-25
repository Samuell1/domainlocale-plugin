<?php namespace Samuell\DomainLocale\Middleware;

use Closure;
use Request;

use RainLab\Translate\Classes\Translator;
use RainLab\Translate\Models\Locale;

class DomainLocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $domain = $request->getHttpHost();
        $locale = Locale::isEnabled()->where('domain', $domain)->first();

        if ($locale) {

            if ($locale->is_domain_redirect && $locale->code != $this->getDefaultLocale()) {
                return redirect($locale->domain);
            }

            $translator = Translator::instance();
            $translator->setLocale($locale->code);
        }

        return $next($request);
    }

    private function getTld($host): string
    {
        return str_replace('.', '', substr(strrchr($host, '.'), 0));
    }

    public function getDefaultLocale()
    {
        $locales = Locale::listAvailable();
        foreach (Request::getLanguages() as $requestLang) {
            if (array_key_exists($requestLang, $locales)) {
                return $requestLang;
            }
        }

        return $this->defaultLocale;
    }
}
