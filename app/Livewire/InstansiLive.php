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
use App\Models\instansiM;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class InstansiLive extends Component
{
    use WithFileUploads;
    #[Locked]
    public $idinstansi;

    public $data, $photos;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        // dd(Storage::disk("s3")->get("Gambar yang ditempelkan.png"));
    }
    public function render()
    {
        
        $instansi = instansiM::where("idinstansi", $this->idinstansi)->select(
            "namainstansi",
            "npsn",
            "kota",
            "alamat",
            "logo",
        )->first();
        $this->data = $instansi->toArray();
        return view('livewire.instansi-live');
    }

    public function updateinstansi()
    {
        
        $this->validate([
            'data.namainstansi' => 'required',
            'data.npsn' => 'required',
            'data.alamat' => 'required',
            'data.kota' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        if($this->validasi()) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        $data = instansiM::findOrFail($this->idinstansi)->update($this->data);

        LivewireAlert::title('Success')->success()->show();
    }

    protected function validasi():bool
    {
        $error = false;
        if(auth()->user()->detailuser->idinstansi != $this->idinstansi) {
            $error = true;
        }

        return $error;
    }

   
    public function updatelogo()
    {
        $this->validate([
            'photos' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        if($this->validasi()) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        $path = $this->imageCompresh("logo", $this->photos);

        instansiM::findOrFail((auth()->user()->detailuser->idinstansi))->update([
            "logo" => $path
        ]);

        $this->photos = "";
        LivewireAlert::title('Success')->success()->show();
    }
    
    
    protected function imageCompresh(String $folder, $file): string
    {
        $quality = 70;

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "$folder/$filename";
        $path192 = "pwa/192/$folder/$filename";
        $path512 = "pwa/512/$folder/$filename";

        $image = Image::decode($file)
        ->scale(height:512);

        $imagepwa192 = Image::decode($file)
        ->cover(192, 192);
        $imagepwa512 = Image::decode($file)
        ->cover(512, 512);

        Storage::disk("s3")->put(
            $path,
            $image->encodeUsingFileExtension($file->getClientOriginalExtension(), quality: $quality)
        );
        Storage::disk("s3")->put(
            $path192,
            $imagepwa192->encodeUsingFileExtension($file->getClientOriginalExtension(), quality: $quality)
        );
        Storage::disk("s3")->put(
            $path512,
            $imagepwa512->encodeUsingFileExtension($file->getClientOriginalExtension(), quality: $quality)
        );

        return $path;
    }
}
