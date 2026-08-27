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
use App\Models\desainkartuM;
use App\Models\detaildesainkartuM;
use App\Models\deskripsikartuM;
use App\Models\datadesainkartuM;
use App\Models\instansiM;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


class DesainkartuLive extends Component
{
    use WithFileUploads;

    #[Validate('file|mimes:jpeg,png,jpg|max:3000')] // 10MB Max
    public $photo, $photo2;

    public $idinstansi;

    public array $data, $detaildesainkartu, $pillbox, $deskripsi;
    public $datainstansi;

    public $tampil = false;

    public $coba = false;
    public function mount()
    {
        $this->pillbox = [
            "nisn",
            "ttl",
            "alamat",
        ];

        $this->deskripsi = [
            "judul" => "KETENTUAN KARTU",
            "deskripsi" => "Kartu ini adalah milik organisasi dan tidak dapat dipindahtangankan.
Jika menemukan kartu ini, harap kembalikan ke kantor sekretariat terdekat.
Kartu wajib dibawa saat menghadiri kegiatan resmi.",
        ];

        $this->detaildesainkartu = ['desainkartu' => 'solid',
            'warnadepan' => '#1a365d',
            'warnabelakang' => '#2b6cb0',
            'warnatextdepan' => '#ffffff',
            'warnatextbelakang' => '#ffffff',
            'warnaborder' => '#ffffff',
            'ganjil' => 'rgba(255, 255, 255, 0.1)',
            'genap' => 'rgba(255, 255, 255, 0.05)',
            'tebalborder' => 3,
            'radiusborder' => 50,
            'gambardepan' => 'profil.png',
            'gambarbelakang' => 'profil.png',
        ];

        $this->idinstansi = session()->get('idinstansi');
        $this->loaddata();
    }
    
    public function loaddata()
    {
        $desainkartu = desainkartuM::where("idinstansi", $this->idinstansi);
        if($desainkartu->count() > 0) {
            $this->tampil = true;
            $detail = $desainkartu->first()->detaildesainkartu()?->first()?->toArray()??[];
            $this->detaildesainkartu = array_merge($this->detaildesainkartu, $detail);
            $deskripsi = $desainkartu->first()->deskripsikartu()->select("judul", "deskripsi")?->first()?->toArray()??[];
            $this->deskripsi = array_merge($this->deskripsi, $deskripsi);
            $desain = $desainkartu->first();

            $desain = $desainkartu->first()->datadesainkartu();
            if($desain->count() > 0) {
               $this->pillbox = $desain->pluck('identitas')->toArray();
            }
        }else {
            $this->tampil =false;
        }
        
    }


    public function render()
    {
        $posisi = auth()->user()->akses->akses;
        $instansi = instansiM::when($this->idinstansi, function ($q) use ($posisi) {
            if ($posisi != "superadmin") {
                $q->where("idinstansi", $this->idinstansi);
            }
        })->get();

        return view('livewire.desainkartu-live', [
            'instansi' => $instansi,
        ]);
    }

    public function simpanDesain()
    {
        $this->validate([
            'detaildesainkartu.desainkartu' => 'required',
            'detaildesainkartu.warnatextdepan' => 'required',
            'detaildesainkartu.warnatextbelakang' => 'required',
            'detaildesainkartu.warnaborder' => 'required',
            'detaildesainkartu.tebalborder' => 'required|numeric',
            'detaildesainkartu.radiusborder' => 'required|numeric',
        ]);

        // dd($this->pillbox);
        if($this->detaildesainkartu["desainkartu"] == "solid") {
            $this->validate([
                'detaildesainkartu.warnadepan' => 'required',
                'detaildesainkartu.warnabelakang' => 'required',
            ]);
        }elseif($this->detaildesainkartu["desainkartu"] == "gambar") {
            // $this->validate([
            //     'photo' => 'required|image|mimes:jpeg,png,jpg|max:3000',
            //     'photo2' => 'required|image|mimes:jpeg,png,jpg|max:3000',
            // ]);

            if($this->photo) {
                $this->detaildesainkartu['gambardepan'] = $this->imageCompresh($this->photo, 1000, 'desainkartu');
            }
            if($this->photo2) {
                $this->detaildesainkartu['gambarbelakang'] = $this->imageCompresh($this->photo2, 1000, 'desainkartu');
            }

        }
        // dd($this->detaildesainkartu);
        detaildesainkartuM::updateOrCreate([
            "iddesainkartu" => desainkartuM::where("idinstansi", $this->idinstansi)->first()->iddesainkartu,
        ], $this->detaildesainkartu);

        
        deskripsikartuM::updateOrCreate([
            "iddesainkartu" => desainkartuM::where("idinstansi", $this->idinstansi)->first()->iddesainkartu,
        ], $this->deskripsi);
        

        // dd($this->pillbox);
        $iddatadesainkartu = desainkartuM::where("idinstansi", $this->idinstansi)->first()->iddesainkartu;
        datadesainkartuM::where("iddesainkartu", $iddatadesainkartu)->delete();
        foreach ($this->pillbox as $key => $data) {
            datadesainkartuM::create([
                "iddesainkartu" => $iddatadesainkartu,
                "identitas" => $data,
                "index" => $key + 1,
            ]);
        }
        $this->loaddata();
        LivewireAlert::title('Success')->success()->show();
        
    }




    public function imageCompresh($file, int $width, $folder): string
    {
        $quality = 92;

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "$folder/$filename";

        $image = Image::decode($file);

        Storage::disk("s3")->put(
            $path,
            $image->encodeUsingFileExtension($file->getClientOriginalExtension(), quality: $quality)
        );

        return $path;
    }
  
    

    public function updateinstansi()
    {
        if(auth()->user()->akses->akses == 'superadmin') {
            $this->idinstansi = $this->datainstansi;
            $this->loaddata();
            
        }
    }

    public function buatdesain()
    {
        desainkartuM::updateOrCreate([
            "idinstansi" => $this->idinstansi,
        ]);
        $this->loaddata();
    }

    
}
