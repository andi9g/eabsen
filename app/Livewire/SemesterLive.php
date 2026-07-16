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
use App\Models\semesterM;
use App\Models\semesteraktifM;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SemesterLive extends Component
{
    use WithPagination;
    #[Locked]
    public $idinstansi;

    public $search, $tahunajaran, $semesteraktif, $idsemester;
    public $data, $update;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $tahunsekarang = (int) date("Y");
        $tahunmin = $tahunsekarang - 2;
        $tahunmax = $tahunsekarang + 1;
        for ($tahunmin; $tahunmin <= $tahunmax; $tahunmin++) { 
           $this->tahunajaran[] = "TP. ".$tahunmin."/".($tahunmin+1);
        }
        $this->update = false;
    }

    public function render()
    {
        $this->idsemester = session()->get("idsemester")??"";
        $this->semesteraktif = semesteraktifM::where([
            "idinstansi" => $this->idinstansi,
            "idsemester" => $this->idsemester,
        ])->first();
        $semester = semesterM::when($this->search, function($query) {
            $query->where("semester", "like", "%$this->search%");
        })->when($this->idinstansi, function($query) {
            $query->where("idinstansi", "$this->idinstansi");
        })
        ->paginate(10);
        return view('livewire.semester-live', [
            "semester" => $semester,
        ]);
    }

    public function buttonformsemester()
    {
        $this->update = false;
        $this->data = [
            "tahunajaran" => "TP. ".((int)date("Y"))."/".((int)date("Y")+1)
        ];

        Flux::modal('buttonformsemester')->show();
    }

    public function buttonupdatesemester($idsemester)
    {
        if($this->validasi($idsemester)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        $semester = semesterM::findOrFail($idsemester);
        $this->update = true;
        $this->data = [
            "idsemester" => $semester->idsemester,
            "semester" => $semester->semester,
            "tahunajaran" => $semester->tahunajaran,
        ];

        Flux::modal('buttonformsemester')->show();
    }


    public function formsemester()
    {
        $this->validate([
            'data.semester' => 'required',
            'data.tahunajaran' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);


        if($this->update) {
            semesterM::findOrFail($this->data["idsemester"])->update([
                "semester" => $this->data["semester"],
                "tahunajaran" => $this->data["tahunajaran"],
            ]);
            $text = "Data berhasil di update.";
        }else {
            if(semesterM::where([
                "semester" => $this->data["semester"],
                "tahunajaran" => $this->data["tahunajaran"],
                "idinstansi" => $this->idinstansi,
            ])->exists()){
                Flux::toast(heading: "Error!", text: "Semester telah ada.", variant: "danger");
                return;
            }
            semesterM::create([
                "idinstansi" => $this->idinstansi,
                "semester" => $this->data["semester"],
                "tahunajaran" => $this->data["tahunajaran"],
            ]);
            $text = "Data berhasil di tambahkan.";
        }

        Flux::modals()->close();
        LivewireAlert::title('Success')->success()->text($text)->show();
    }


    public function ubahaktif($idsemester, $aktif)
    {
        if($this->validasi($idsemester)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        if(semesteraktifM::where([
            "idinstansi" => $this->idinstansi,
            "idsemester" => $idsemester,
        ])->exists() && !$aktif){
            LivewireAlert::title('error')->text("Maaf, yang aktif tidak dapat dimatikan. harap pilih yang lain.")->error()->show();
        }

        semesteraktifM::updateOrCreate([
            "idinstansi" => $this->idinstansi,
        ], [
            "idsemester" => $idsemester
        ]);

        session()->put("idsemester", $idsemester);
        $this->semesteraktif = semesteraktifM::where([
            "idinstansi" => $this->idinstansi,
            "idsemester" => session()->get("idsemester"),
        ])->first();
        Flux::toast(variant: "success", text: "Semester Berhasil di aktifkan.");
    }

    protected function validasi($idsemester):bool
    {
        $error = false;

        if(!(auth()->user()->detailuser->instansi->idinstansi == $this->idinstansi)) {
            $error = true;
        }

        $semester = semesterM::findOrFail($idsemester);
        if($semester->idinstansi != $this->idinstansi) {
            $error = true;
        }

        return $error;
    }
}
