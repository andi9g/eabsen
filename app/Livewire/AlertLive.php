<?php

namespace App\Livewire;

use Livewire\Component;
use Session;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class AlertLive extends Component
{
    public function mount()
    {
        if(Session::has("error")) {
            LivewireAlert::title(Session::get("error"))->asToast()->error()->show();
        }
        if(Session::has("warning")) {
            LivewireAlert::title(Session::get("warning"))->asToast()->warning()->show();
        }
        if(Session::has("success")) {
            LivewireAlert::title(Session::get("success"))->asToast()->success()->show();
        }
    }
    // public function render()
    // {
    //     return view('livewire.alert-live');
    // }
}
