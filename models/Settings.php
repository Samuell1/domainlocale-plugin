<?php namespace Samuell\DomainLocale\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'samuell_domainlocale_settings';

    public $settingsFields = 'fields.yaml';
}
