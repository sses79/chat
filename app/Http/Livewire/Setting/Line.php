<?php

namespace App\Http\Livewire\Setting;

use App\Models\Setting;
use Livewire\Component;

class Line extends Component
{
    public $line_setting = 'vic';

    public $lines;

    protected $listeners = ['updateLine', 'alertRemove'];


    public function mount()
    {
        $this->lines = collect([
            'vic' => 'victoria',
            'cen' => 'central',
            'jub' => 'jubilee',
        ]);

        $current_data_setting = Setting::where('name', 'current_data')->first();

        if (!empty($current_data_setting)) {
            $current_data = json_decode($current_data_setting->payload, true);
            if (isset($current_data["current_line"])) {
                $this->line_setting = $current_data["current_line"];
            }
        }
    }

    public function updateLine(string $line)
    {
        $current_data_setting = Setting::where('name', 'current_data')->first();

        if (!empty($current_data_setting)) {
            $current_data = json_decode($current_data_setting->payload, true);

            $current_data['current_line'] = $line;
            $current_data_setting->payload = json_encode($current_data);
            $current_data_setting->save();
        }

        $this->line_setting = $line;

        session()->flash('message', 'Line Setting Changed.');

        $this->emit('alertRemove', 2);

    }

    public function alertRemove($second)
    {
        sleep($second);
        session()->flash('message', '');
    }

    public function render()
    {
        return view('livewire.setting.line');
    }

}
