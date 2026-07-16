<?php

namespace App\Livewire;

use Livewire\Component;
use App\Attributes\Locked;
use Auth;
use Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\pengaturanM;
use App\Models\hariM;
use App\Models\hariliburM;

class JamoperasionalLive extends Component
{
    #[Locked]
    public $idinstansi;

    public $data, $datahari;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->datahari = hariM::get();
        // $this->data[] = hariliburM::where("idinstansi", $this->idinstansi)->get()->pluck("idhari")->map('strval')->toArray(); 
        $pengaturan = pengaturanM::where("idinstansi", $this->idinstansi)->first();
        $this->data = [
            "jammasuk" => $pengaturan->jammasuk??"",
            "jampulang" => $pengaturan->jampulang??"",
            "keterlambatan" => $pengaturan->keterlambatan??"15",
            "harilibur" => hariliburM::where("idinstansi", $this->idinstansi)->get()->pluck("idhari")->map('strval')->toArray(),
        ];
        // dd($this->data["harilibur"]);
    }

    public function render()
    {
        
        return view('livewire.jamoperasional-live');
    }

    public function formjamoperasional()
    {
        $this->validate([
            'data.jammasuk' => 'required',
            'data.jampulang' => 'required',
            'data.keterlambatan' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        pengaturanM::updateOrCreate([
            'idinstansi' => $this->idinstansi,
            ],[
            'jammasuk' => $this->data["jammasuk"],
            'jampulang' => $this->data["jampulang"],
            'keterlambatan' => $this->data["keterlambatan"],
        ]);

        LivewireAlert::title('Success')->success()->show();
    }

    public function harilibur()
    {
        $this->validate([
            'data.harilibur' => 'required|array',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        // dd($this->data["harilibur"]);
        $harilibur = collect($this->data["harilibur"])->map(function ($value) {
            return [
                'idhari' => $value,
                'idinstansi' => $this->idinstansi,
                ];
        })->all();
        
        hariliburM::where("idinstansi", $this->idinstansi)->delete();
        hariliburM::insert($harilibur);
        LivewireAlert::title('Success')->success()->show();
        
    }
}
