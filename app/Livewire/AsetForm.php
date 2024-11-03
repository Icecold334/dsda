<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Option;
use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AsetForm extends Component
{



    public $aset;
    #[Validate]
    public $nama;
    #[Validate]
    public $kode;
    public $kategoris;
    public $kategori;
    use WithFileUploads;
    #[Validate]
    public $img;
    #[Validate]
    public $merk;
    public $tipe;
    public $produsen;
    public $noseri;
    public $tahunProduksi;
    public $deskripsi;
    #[Validate]
    public $tanggalPembelian;
    public $toko;
    public $invoice;
    public $jumlah;
    public $hargaSatuan;
    public $hargaTotal;
    public $attachments = [];
    public $newAttachments = [];
    public $keterangan;
    #[Validate]
    public $umur;
    public $penyusutan;



    public function rules()
    {
        $kodeRule = false ? 'required|string|max:100|unique:aset,kode,' . $this->aset : 'required|string|max:100';

        return [
            'nama' => 'required|string|max:255',
            'kode' => $kodeRule,
            'merk' => 'required|string|max:255',
            'tanggalPembelian' => 'required|date',
            'toko' => 'required|string|max:255',
            'invoice' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'hargaSatuan' => 'required|min:0',
            'umur' => 'required|min:1',
            'img' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'  // Assuming 'img' is the image upload field
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Aset wajib diisi!',
            'nama.string' => 'Nama Aset harus berupa teks!',
            'nama.max' => 'Nama Aset tidak boleh lebih dari 255 karakter!',
            'kode.required' => 'Kode Aset wajib diisi!',
            'kode.string' => 'Kode Aset harus berupa teks!',
            'kode.max' => 'Kode Aset tidak boleh lebih dari 100 karakter!',
            'kode.unique' => 'Kode Aset sudah digunakan!',
            'kategori.required' => 'Kategori wajib diisi!',
            'kategori.exists' => 'Kategori tidak valid!',
            'img.image' => 'File harus berupa gambar!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'img.mimes' => 'Format gambar harus jpeg, png, jpg, gif!',
            'merk.required' => 'Merk wajib diisi!',
            'umur.required' => 'Umur wajib diisi!',
            'umur.min' => 'Umur minimal 1 tahun!',
            'merk.string' => 'Merk harus berupa teks!',
            'merk.max' => 'Merk tidak boleh lebih dari 255 karakter!',
            'merk.unique' => 'Merk sudah terdaftar, pilih merk lain!',
            'tanggalPembelian.required' => 'Tanggal pembelian wajib diisi!',
            'tanggalPembelian.date' => 'Tanggal pembelian harus berupa tanggal yang valid!',
            'toko.required' => 'Nama toko/distributor wajib diisi!',
            'toko.string' => 'Nama toko/distributor harus berupa teks!',
            'toko.max' => 'Nama toko/distributor tidak boleh lebih dari 255 karakter!',
            'invoice.string' => 'Nomor invoice harus berupa teks!',
            'invoice.max' => 'Nomor invoice tidak boleh lebih dari 255 karakter!',
            'jumlah.required' => 'Jumlah wajib diisi!',
            'jumlah.integer' => 'Jumlah harus berupa angka!',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 1!',
            'hargaSatuan.required' => 'Harga satuan wajib diisi!',
            'hargaSatuan.numeric' => 'Harga satuan harus berupa angka!',
            'hargaSatuan.min' => 'Harga satuan tidak boleh kurang dari 0!',
            'img.image' => 'File harus berupa gambar!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'img.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif!'
        ];
    }
    public function mount()
    {
        $this->kategoris = Kategori::all();

        if ($this->aset) {
            $this->img = $this->aset->foto;
            $this->nama = $this->aset->nama;
            $this->kode = $this->aset->kode;
            $this->kategori = $this->aset->kategori_id;
            $this->merk = $this->aset->merk->nama;
            $this->tipe = $this->aset->tipe;
            $this->produsen = $this->aset->produsen;
            $this->noseri = $this->aset->noseri;
            $this->tahunProduksi = $this->aset->tahunProduksi;
            $this->deskripsi = $this->aset->deskripsi;
            $this->tanggalPembelian = $this->aset->tanggalPembelian;
            $this->toko = $this->aset->toko->nama;
            $this->invoice = $this->aset->invoice;
            $this->jumlah = $this->aset->jumlah;
            $this->hargaSatuan = $this->aset->hargaSatuan;
            $this->hargaTotal = $this->aset->hargaTotal;
            $this->umur = $this->aset->umur;
            // $this->attachments = $this->aset->attachments;  // Assuming attachments are stored as an array or similar structure
            $this->keterangan = $this->aset->keterangan;
        } else {
            $this->kode = $this->autoKodeaset(); // Set kode aset only if creating new
        }
    }

    private function autoKodeaset()
    {
        $templateKodeaset = Option::find(1)->kodeaset;
        $user_id = Auth::user()->id; // Get the current logged-in user's ID

        // Get the count of Aset where 'prepublish' is 0
        $jumlahAset = Aset::where('user_id', $user_id)
            ->where('prepublish', 0)
            ->count();

        $nomor = $jumlahAset + 1;

        // Using Carbon for date manipulation
        $tanggal = Carbon::now()->format('d');
        $bulanAngka = Carbon::now()->format('m');
        $bulanRomawi = $this->bulanRomawi($bulanAngka);
        $tahun = Carbon::now()->format('Y');

        // Execute replacements
        $kodeAset = str_replace(
            ['[nomor]', '[tanggal]', '[bulan-angka]', '[bulan-romawi]', '[tahun]'],
            [$nomor, $tanggal, $bulanAngka, $bulanRomawi, $tahun],
            $templateKodeaset
        );

        return $kodeAset;
    }

    private function bulanRomawi($bulan)
    {
        $map = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII'
        ];
        return $map[$bulan] ?? '';
    }

    public function updatedImg()
    {
        // Check if the file is an image
        if ($this->img && in_array($this->img->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            // Proceed with any operations such as saving the file
            // $path = $this->img->store('public/images', 'public');
            // Optionally perform additional actions like updating the database
        } else {
            // If the file is not an image, reset the image and show an error
            $this->reset('img');  // This will clear the current image without affecting other properties
            $this->dispatch('swal:error', [
                'text' => 'File yang diunggah bukan gambar. Silakan coba lagi dengan file gambar.',
            ]);
        }
    }
    public function removeImg()
    {
        $this->img = null;
    }

    public function updatedNewAttachments()
    {
        $this->validate([
            'newAttachments.*' => 'max:5024', // Validation for each new attachment
        ]);

        foreach ($this->newAttachments as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->attachments[] = $file;
        }

        // Clear the newAttachments to make ready for next files
        $this->reset('newAttachments');
    }
    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            // If it's a file path, delete the file
            // Storage::disk('public')->delete($this->attachments[$index]);
            // Remove from the array
            unset($this->attachments[$index]);
            // Reindex array
            $this->attachments = array_values($this->attachments);
        }
    }

    public function saveAset()
    {
        $this->validate();
        // Ensure Merk and Toko IDs are set correctly or created if they don't exist
        $merkId = $this->getOrCreateMerk($this->merk);
        $tokoId = $this->getOrCreateToko($this->toko);
        $data = [
            'user_id' => Auth::id(),
            'nama' => $this->nama,
            'kode' => $this->kode,
            'foto' => $this->img ? str_replace('asetImg/', '', $this->img->store('asetImg', 'public')) : null,
            'systemcode' => $this->aset ?   $this->aset->systemcode : $this->generateQRCode(),
            'kategori_id' => $this->kategori,
            'merk_id' => $merkId,
            'tipe' => $this->tipe,
            'produsen' => $this->produsen,
            'noseri' => $this->noseri,
            'thproduksi' => $this->tahunProduksi,
            'deskripsi' => $this->deskripsi,
            'tanggalbeli' => $this->tanggalPembelian ? Carbon::createFromFormat('Y-m-d', $this->tanggalPembelian)->format('Ymd') : null,
            'toko_id' => $tokoId,
            'invoice' => $this->invoice,
            'jumlah' => $this->jumlah,
            'hargasatuan' => $this->cleanCurrency($this->hargaSatuan),
            'hargatotal' => $this->cleanCurrency($this->hargaTotal),
            'umur' => $this->umur,
            'penyusutan' => $this->cleanCurrency($this->penyusutan),
        ];

        // Insert or update aset based on $this->aset
        Aset::updateOrCreate(['id' => $this->aset->id], $data);

        // Optionally add flash message or other post-save actions
        session()->flash('message', 'Aset successfully saved.');
        return redirect()->to('/path-to-redirect');
    }

    /**
     * Convert formatted currency to integer.
     *
     * @param string $value
     * @return int
     */
    private function cleanCurrency($value)
    {
        return (int) str_replace(["Rp", "\u{A0}", ",", "."], '', $value);
    }

    /**
     * Get or create Merk by name.
     *
     * @param string $name
     * @return int
     */
    private function getOrCreateMerk($name)
    {
        $merk = Merk::firstOrCreate(['nama' => $name], ['user_id' => Auth::id()]);
        return $merk->id;
    }

    /**
     * Get or create Toko by name.
     *
     * @param string $name
     * @return int
     */
    private function getOrCreateToko($name)
    {
        $toko = Toko::firstOrCreate(['nama' => $name], ['user_id' => Auth::id()]);
        return $toko->id;
    }

    private function calculateDepreciation($totalValue, $years)
    {
        if ($years > 0) {
            return $totalValue / ($years * 12);  // monthly depreciation
        }
        return 0;
    }

    private
    function generateQRCode()
    {
        $userId = Auth::id(); // Assuming you have authentication and you get the user's ID
        $qrName = strtoupper(Str::random(16)); // Generate an uppercase random string for the QR code filename

        // Define the folder path
        $qrFolder = "qr";

        // Full path for the QR code image
        $qrTarget = "{$qrFolder}/{$qrName}.png";

        // Content to encode in the QR code, using path parameters instead of query parameters
        $qrContent = url("/scan/{$userId}/{$qrName}");

        // Ensure the directory exists
        // Storage::disk('public')->makeDirectory($qrFolder);


        // Or use Laravel's Storage facade to handle file stream
        Storage::disk('public')->put($qrTarget, QrCode::format('png')->size(300)->generate($qrContent));


        // Check if the file was created successfully
        if (Storage::disk('public')->exists($qrTarget)) {
            return $qrName; // Return the QR code file name (random string)
        } else {
            return "0"; // Return "0" if the QR code wasn't created
        }
    }

    public function render()
    {
        return view('livewire.aset-form');
    }
}
