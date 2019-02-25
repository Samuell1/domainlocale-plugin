<?php namespace Samuell\DomainLocale;

use Backend;
use System\Classes\PluginBase;
use Cms\Classes\CmsController;
use Samuell\DomainLocale\Middleware\DomainLocaleMiddleware;
use Rainlab\Translate\Controllers\Locales as LocalesController;
use RainLab\Translate\Models\Locale as LocaleModel;

/**
 * DomainLocale Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.Translate'];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'DomainLocale',
            'description' => 'Set locale depending on domain',
            'author'      => 'Samuell',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Use middleware to redirect depending on language
        CmsController::extend(function ($controller) {
            $controller->middleware(DomainLocaleMiddleware::class);
        });

        LocalesController::extendFormFields(function($form, $model, $context) {

            if (!$model instanceof LocaleModel) {
                return;
            }

            // Update content field
            $form->addFields([
                'domain' => [
                    'label' => 'Domain',
                    'type' => 'text',
                    'span' => 'left'
                ],
                'is_domain_redirect' => [
                    'label' => 'Redirect to this domain',
                    'type' => 'checkbox',
                    'span' => 'right'
                ],
            ]);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Samuell\DomainLocale\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'samuell.domainlocale.some_permission' => [
                'tab' => 'DomainLocale',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'domainlocale' => [
                'label'       => 'DomainLocale',
                'url'         => Backend::url('samuell/domainlocale/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['samuell.domainlocale.*'],
                'order'       => 500,
            ],
        ];
    }
}
