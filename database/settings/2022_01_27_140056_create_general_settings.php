<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Demo');
        $this->migrator->add('general.api_key', 'Gm638pb1jA');
        $this->migrator->add('general.current_data', ["updated_time" => "2021-10-04 16:00:00"]);
    }
}
