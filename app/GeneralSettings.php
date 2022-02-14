<?php

namespace App;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $api_key;

    public array $current_data;

    public static function group(): string
    {
        return 'general';
    }

}
