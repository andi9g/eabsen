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
use App\Models\kelasM;
use App\Models\jurusanM;
use App\Models\absensiswaM;
use App\Models\siswaM;
use App\Models\pengaturanM;


class AbsensiswaLive extends Component
{
    use WithPagination;

    #[Locked]
    public $idinstansi, $idsemester, $kelas, $jurusan, $jammasuk, $keterlambatan;

    public $data, $dataUpdate;
    public $datatampil, $tampil;
    public $search, $idkelas, $idjurusan, $tanggal;

    public function mount()
    {
        $this->idinstansi = session("idinstansi")??"";
        $this->idsemester = session("semester")??"";
        $kelas = kelasM::where("idinstansi", $this->idinstansi)->get();
        $jurusan = jurusanM::where("idinstansi", $this->idinstansi)->get();
        $pengaturan = pengaturanM::where("idinstansi", $this->idinstansi)->first();
        $this->jammasuk = $pengaturan->jammasuk??'08:00:00';
        $this->keterlambatan = $pengaturan->keterlambatan??15;
        $this->kelas = $kelas;
        $this->jurusan = $jurusan;  
        $this->search = "";
        $this->tanggal = now()->toDateString();
        $this->tanggalbaru($this->tanggal);
        $this->datatampil = [
            "10" => "10 Data",
            "20" => "20 Data",
            "30" => "30 Data",
            "40" => "40 Data",
        ];
        $this->tampil = 10;
    }

    public function tanggalbaru($tanggal)
    {
        $this->data = [];
        $this->data = absensiswaM::where('idinstansi', $this->idinstansi)
        ->where('tanggal', $tanggal)
        ->pluck('status', 'idsiswa')
        ->toArray();
        dd(
            absensiswaM::where('idinstansi', $this->idinstansi)
                ->where('tanggal', $tanggal)
                ->get()
        );
    }

    public function pilihtanggal()
    {
        $this->tanggalbaru($this->tanggal);
    }

    public function render()
    {
        
        $siswa = siswaM::where("idinstansi", $this->idinstansi)
            ->whereHas("kelas")
            ->whereHas("jurusan")
            ->when($this->idkelas, function ($query) {
                $query->where("idkelas", $this->idkelas);
            })
            ->when($this->idjurusan, function ($query) {
                $query->where("idjurusan", $this->idjurusan);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where("namasiswa", "like", "%" . $this->search . "%")
                        ->orWhere("nisn", "like", "%" . $this->search . "%");
                });
            })
            ->orderBy("namasiswa", "asc")
            ->paginate($this->tampil);

            $this->validasi($siswa->first()->idsiswa);
        return view('livewire.absensiswa-live', [
            'siswa' => $siswa,
        ]);
    }

    public function saveChanges()
    {
        $dataUpdate = [];
        foreach ($this->dataUpdate as $idsiswa => $status) {
            if($this->validasi($idsiswa)) {
                $dataUpdate[] = [
                    'idinstansi' => $this->idinstansi,
                    'idsiswa' => $idsiswa,
                    'tanggal' => $this->tanggal,
                    'waktumasuk' => now(),
                    'status' => $status,
                ];
            }
        }
        dd($this->data);
        
        absensiswaM::upsert(
            $dataUpdate,
            ['idinstansi', 'idsiswa', 'tanggal'],
            ['waktumasuk', 'status']
        );
        $this->dataUpdate = [];
        LivewireAlert::title('Success')->success()->show();
    }

    public function changeState($idsiswa, $value)
    {
        $this->data[$idsiswa] = $value;
        $this->dataUpdate[$idsiswa] = $value;
    }

    protected function validasi($idsiswa):bool
    {
        $siswa = siswaM::where([
            "idinstansi" => $this->idinstansi,
            "idsiswa" => $idsiswa,
        ])->exists();
        return $siswa;
    }
}
