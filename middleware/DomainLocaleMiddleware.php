<?php namespace Samuell\DomainLocale\Middleware;

use Closure;

use RainLab\Translate\Classes\Translator;
use RainLab\Translate\Models\Locale;
use Samuell\DomainLocale\Models\Settings;

use Samuell\DomainLocale\Classes\Helper;

class DomainLocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $domain = Settings::get('use_tld', false)
            ? Helper::getDomainTld($request->getHttpHost())
            : $request->getHttpHost();

        $locale = Locale::isEnabled()->where('domain', $domain)->first();

        // if user language is not same as domain we redirect him to correct language domain if exists
        if (Settings::get('auto_domain_redirect', false) && $locale->code != Helper::getUserLocale()) {
            $isUriEmpty = Settings::get('redirec_only_empty_uri', false)
                ? empty(trim($request->getRequestUri(), '/'))
                : true;
            // Redirect to other domain but only when Uri is empty
            if ($redirectLocale = Locale::findByCode(Helper::getUserLocale()) && $isUriEmpty) {
                return redirect($this->addHttpToUrl($redirectLocale->domain));
            }
        }

        // set locale
        if ($locale) {
            $translator = Translator::instance();
            $translator->setLocale($locale->code);
        }

        return $next($request);
    }

    public function addHttpToUrl($url)
    {
        $parsed = parse_url($url);
        if (empty($parsed['scheme'])) {
            return 'http://'.ltrim($url, '/');
        }
        return $url;
    }
}
