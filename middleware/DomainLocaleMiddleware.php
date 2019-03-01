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

        if ($locale) {

            // if user language is not same as domain we redirect him to correct language domain if exists
            if (Settings::get('auto_domain_redirect', false) && $locale->code != Helper::getUserLocale()) {

                // allow redirect only for default domain
                $allowRedirect = Settings::get('use_default_only_for_redirect', false)
                    ? $locale->is_default
                    : true;

                // redirect to other domain depending if URI is empty
                $isUriEmpty = Settings::get('redirec_only_empty_uri', false)
                    ? empty(trim($request->getRequestUri(), '/'))
                    : true;

                $redirectLocale = Locale::findByCode(Helper::getUserLocale());
                if ($redirectLocale && $isUriEmpty && $allowRedirect) {
                    return redirect($this->addHttpToUrl($redirectLocale->domain));
                }

            }

            // set locale
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
