<?php

namespace Tests\Feature;

use App\GeneralSettings;
use App\Http\Livewire\Setting\Line;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SelectLineTest extends TestCase
{
    public function test_users_can_change_line_setting()
    {
        $this->actingAs($user = User::find(1));

        Livewire::test(Line::class)
            ->call('updateLine', 'cen');

        $current_data_setting = app(GeneralSettings::class)->current_data;

        $this->assertEquals('cen', $current_data_setting['current_line']);

    }
}
