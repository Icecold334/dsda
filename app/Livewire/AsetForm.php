<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\Option;
use App\Models\Garansi;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\Lampiran;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

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
    public $merk_id;
    public $merk;
    public $tipe;
    public $produsen;
    public $noseri;
    public $tahunProduksi;
    public $deskripsi;
    #[Validate]
    public $tanggalPembelian;
    public $toko_id;
    public $toko;
    public $invoice;
    public $jumlah;
    public $hargaSatuan;
    public $hargasatuan;
    public $hargaTotal;
    public $attachments = [];
    public $oldattachments = [];
    public $newAttachments = [];
    public $garansiattachments = [];
    public $oldgaransiattachments = [];
    public $newGaransiAttachments = [];
    public $keterangan;
    #[Validate]
    public $umur;
    public $lamagaransi;
    public $penyusutan;
    public $showSuggestionsMerk;
    public $suggestionsMerk;
    public $showSuggestionsToko;
    public $suggestionsToko;

    public function focusMerk()
    {
        $this->searchQueryMerk();
    }

    public function searchQueryMerk()
    {
        // dd('aa');
        $this->showSuggestionsMerk = true;

        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Ambil data dari database berdasarkan query
        $this->suggestionsMerk = Merk::where('nama', 'like', '%' . $this->merk . '%')
            ->whereHas('user', function ($query) use ($parentUnitId) {
                // Menggunakan helper untuk memfilter unit
                filterByParentUnit($query, $parentUnitId);
            })
            // ->limit(5)
            ->get()
            ->toArray();
        // $this->merk = $this->merk;

        $exactMatchMerk = Merk::where('nama', $this->merk)->first();

        if ($exactMatchMerk) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectSuggestionMerk($exactMatchMerk->id, $exactMatchMerk->nama);
        }
    }

    public function selectSuggestionMerk($merkId, $merkName)
    {
        // Ketika saran dipilih, isi input dengan nilai tersebut
        $this->merk_id = $merkId;
        $this->merk = $merkName;
        $this->suggestionsMerk = [];
        $this->hideSuggestionsMerk();
    }

    public function hideSuggestionsMerk()
    {
        // $this->suggestions = [];
        $this->showSuggestionsMerk = false;
    }
    public function focusToko()
    {
        $this->searchQueryToko();
    }

    public function searchQueryToko()
    {
        $this->showSuggestionsToko = true;

        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Ambil data dari database berdasarkan query
        $this->suggestionsToko = Toko::where('nama', 'like', '%' . $this->toko . '%')
            ->whereHas('user', function ($query) use ($parentUnitId) {
                // Menggunakan helper untuk memfilter unit
                filterByParentUnit($query, $parentUnitId);
            })
            // ->limit(5)
            ->get()
            ->toArray();
        // $this->nama = $this->toko;

        $exactMatchToko = Toko::where('nama', $this->toko)->first();

        if ($exactMatchToko) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectSuggestionToko($exactMatchToko->id, $exactMatchToko->nama);
        }
    }

    public function selectSuggestionToko($tokoId, $tokoName)
    {
        // Ketika saran dipilih, isi input dengan nilai tersebut
        $this->toko_id = $tokoId;
        $this->toko = $tokoName;
        $this->suggestionsToko = [];
        $this->hideSuggestionsToko();
    }

    public function hideSuggestionsToko()
    {
        // $this->suggestions = [];
        $this->showSuggestionsToko = false;
    }


    public function rules()
    {
        $kodeRule = false ? 'required|string|max:100|unique:aset,kode,' . $this->aset : 'required|string|max:100';
        $imgRule = is_string($this->img) ? '' : 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif';

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
            'img' => $imgRule,
            'attachments.*' => 'file|max:5024|mimes:jpeg,png,jpg,gif,pdf,doc,docx',
            // 'attachments' => 'array|max:10',
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
            'attachments.*.file' => 'Setiap lampiran harus berupa file.',
            'attachments.*.max' => 'Setiap lampiran tidak boleh lebih dari 5 MB.',
            'attachments.*.mimes' => 'Lampiran harus berupa file dengan format jpeg, png, jpg, gif, pdf, doc, atau docx.',

        ];
    }
    public function mount()
    {
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        // Tentukan parentUnitId
        // Jika unit memiliki parent_id (child), gunakan parent_id-nya
        // Jika unit tidak memiliki parent_id (parent), gunakan unit_id itu sendiri
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        // Debugging: Tampilkan parentUnitId untuk verifikasi
        // dd($parentUnitId);

        // $this->kategoris = Kategori::all();
        $this->kategoris = Kategori::whereHas('user', function ($query) use ($parentUnitId) {
            // Menggunakan helper untuk memfilter unit
            filterByParentUnit($query, $parentUnitId);
        })->get();

        if ($this->aset) {
            $this->img = $this->aset->foto;
            $this->nama = $this->aset->nama;
            $this->kode = $this->aset->kode;
            $this->kategori = $this->aset->kategori_id;
            $this->merk = $this->aset->merk->nama;
            $this->tipe = $this->aset->tipe;
            $this->produsen = $this->aset->produsen;
            $this->noseri = $this->aset->noseri;
            $this->tahunProduksi = $this->aset->thproduksi;
            $this->deskripsi = $this->aset->deskripsi;
            $this->tanggalPembelian = $this->aset->tanggalPembelian;
            $this->toko = $this->aset->toko->nama;
            $this->invoice = $this->aset->invoice;
            $this->jumlah = $this->aset->jumlah;
            $this->hargasatuan = $this->aset->hargasatuan;
            // $this->hargaTotal = $this->aset->hargatotal;
            $this->hargaSatuan = $this->formatRupiah($this->aset->hargasatuan);
            $this->hargaTotal = $this->formatRupiah($this->aset->hargatotal);
            $this->umur = $this->aset->umur;
            $this->lamagaransi = $this->aset->lama_garansi;
            // $this->attachments = $this->aset->attachments;  // Assuming attachments are stored as an array or similar structure
            $this->keterangan = $this->aset->keterangan;
            // Ambil lampiran yang terkait dengan aset
            $this->oldattachments = Lampiran::where('aset_id', $this->aset->id)->get();
            $this->oldgaransiattachments = Garansi::where('aset_id', $this->aset->id)->get();
            // dd($this->attachments);

            // Hitung penyusutan jika umur lebih besar dari 0
            if ($this->umur > 0) {
                $total = $this->jumlah * $this->hargasatuan; // Harga Total
                $bulan = $this->umur * 12; // Menghitung bulan dari umur dalam tahun
                $this->penyusutan = $total / $bulan; // Penyusutan per bulan
                $this->penyusutan =  $this->formatRupiah($this->penyusutan); // Penyusutan per bulan
            } else {
                $this->penyusutan = 0; // Jika umur tidak valid, set penyusutan ke 0
            }
        } else {
            $this->kode = $this->autoKodeaset(); // Set kode aset only if creating new
        }
    }
    // Helper function untuk format mata uang Rupiah
    private function formatRupiah($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
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
            ['[nomor]', '[tanggal]', '[bulan_angka]', '[bulan_romawi]', '[tahun]'],
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
            'newAttachments.*' => 'file|max:5024|mimes:jpeg,png,jpg,gif,pdf,doc,docx', // Validation for each new attachment
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

    public function removeOldAttachment($id)
    {
        $lampiran = Lampiran::find($id);

        if ($lampiran) {
            // Hapus file dari penyimpanan
            Storage::disk('public')->delete('LampiranAset/' . $lampiran->file);

            // Hapus dari database
            $lampiran->delete();

            // Refresh daftar lampiran lama
            // $this->attachments = Lampiran::where('aset_id', $this->aset->id)->get();
            return redirect()->route('aset.edit', $this->aset->id);
        }
    }

    public function updatedNewGaransiAttachments()
    {
        $this->validate([
            'newAttachments.*' => 'max:5024', // Validation for each new attachment
        ]);

        foreach ($this->newGaransiAttachments as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->garansiattachments[] = $file;
        }

        // Clear the newAttachments to make ready for next files
        $this->reset('newAttachments');
    }

    public function removeGaransiAttachment($index)
    {
        if (isset($this->garansiattachments[$index])) {
            // If it's a file path, delete the file
            // Storage::disk('public')->delete($this->attachments[$index]);
            // Remove from the array
            unset($this->garansiattachments[$index]);
            // Reindex array
            $this->garansiattachments = array_values($this->garansiattachments);
        }
    }

    public function removeOldGaransiAttachment($id)
    {
        $garansi = Garansi::find($id);

        if ($garansi) {
            // Hapus file dari penyimpanan
            Storage::disk('public')->delete('GaransiAset/' . $garansi->file);

            // Hapus dari database
            $garansi->delete();

            // Refresh daftar lampiran lama
            // $this->attachments = Lampiran::where('aset_id', $this->aset->id)->get();
            return redirect()->route('aset.edit', $this->aset->id);
        }
    }

    public function saveAset()
    {
        $this->validate();
        // dd(strtotime($this->tanggalPembelian)); // Debug hasil dari strtotime
        // dd($this->merk);
        // Ensure Merk and Toko IDs are set correctly or created if they don't exist
        $merkId = $this->getOrCreateMerk($this->merk);
        $tokoId = $this->getOrCreateToko($this->toko);
        $data = [
            'user_id' => !$this->aset ? Auth::id() : $this->aset->user_id,
            'nama' => $this->nama,
            'kode' => $this->kode,
            // 'foto' => !is_string($this->img) ? str_replace('asetImg/', '', $this->img->store('asetImg', 'public')) : $this->img ?? null,
            'foto' => $this->img
                ? (is_object($this->img)
                    ? str_replace('asetImg/', '', $this->img->store('asetImg', 'public'))
                    : $this->img)
                : null,
            'systemcode' => $this->aset ?   $this->aset->systemcode : $this->generateQRCode(),
            'kategori_id' => $this->kategori,
            'merk_id' => $merkId,
            'tipe' => $this->tipe,
            'produsen' => $this->produsen,
            'noseri' => $this->noseri,
            'thproduksi' => $this->tahunProduksi,
            'deskripsi' => $this->deskripsi,
            // 'tanggalbeli' => $this->tanggalPembelian ? Carbon::createFromFormat('Y-m-d', $this->tanggalPembelian)->format('Ymd') : null,
            'tanggalbeli' => $this->tanggalPembelian ? strtotime(Carbon::createFromFormat('Y-m-d', $this->tanggalPembelian)->toDateString()) : null,
            'toko_id' => $tokoId,
            'invoice' => $this->invoice,
            'jumlah' => $this->jumlah,
            'lama_garansi' => $this->lamagaransi,
            'keterangan' => $this->keterangan,
            'hargasatuan' => $this->cleanCurrency($this->hargaSatuan),
            'hargatotal' => $this->cleanCurrency($this->hargaTotal),
            'umur' => $this->umur,
            'penyusutan' => $this->cleanCurrency($this->penyusutan),
        ];

        // Insert or update aset based on $this->aset
        $aset = Aset::updateOrCreate(['id' => $this->aset->id ?? 0], $data);

        // Handle attachments
        $this->saveAttachments($aset->id);

        // Optionally add flash message or other post-save actions
        session()->flash('message', 'Aset successfully saved.');
        return redirect()->route('aset.show', $aset);
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
        $merk = Merk::firstOrCreate(['user_id' => Auth::user()->id, 'nama' => $name, 'nama_nospace' => Str::slug($name)]);
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
        $toko = Toko::firstOrCreate(['user_id' => Auth::user()->id, 'nama' => $name, 'nama_nospace' => Str::slug($name)]);
        return $toko->id;
    }

    private function calculateDepreciation($totalValue, $years)
    {
        if ($years > 0) {
            return $totalValue / ($years * 12);  // monthly depreciation
        }
        return 0;
    }

    protected function saveAttachments($asetId)
    {
        // Process regular attachments
        $this->saveAttachmentFiles($this->attachments, $asetId, 'LampiranAset', Lampiran::class);

        // Process garansi attachments
        $this->saveAttachmentFiles($this->garansiattachments, $asetId, 'GaransiAset', Garansi::class);
    }

    private function saveAttachmentFiles($files, $asetId, $folder, $model)
    {
        if ($files) {
            foreach ($files as $file) {
                // Store file to specific folder in public disk
                $filePath = $this->storeFile($file, $folder);

                // Save file info to the appropriate model
                $model::create([
                    'user_id' => Auth::user()->id,
                    'aset_id' => $asetId, // Associate with the Aset
                    'file' => $filePath,
                ]);
            }

            // Clear the attachments array after saving
            $this->clearAttachments($files);
        }
    }

    private function storeFile($file, $folder)
    {
        return str_replace("{$folder}/", '', $file->storeAs($folder, $file->getClientOriginalName(), 'public'));
    }

    private function clearAttachments(&$files)
    {
        $files = [];
    }



    private function generateQRCode()
    {
        $userId = Auth::id(); // Dapatkan ID pengguna yang login
        $qrName = strtoupper(Str::random(16)); // Buat nama file acak untuk QR code

        // Tentukan folder dan path target file
        $qrFolder = "qr";
        $qrTarget = "{$qrFolder}/{$qrName}.png";

        // Konten QR Code (contohnya URL)
        $qrContent = url("/scan/{$userId}/{$qrName}");

        // Pastikan direktori untuk QR Code tersedia
        if (!Storage::disk('public')->exists($qrFolder)) {
            Storage::disk('public')->makeDirectory($qrFolder);
        }

        // Konfigurasi renderer untuk menggunakan GD dengan ukuran 400x400
        $renderer = new GDLibRenderer(500);
        $writer = new Writer($renderer);

        // Path absolut untuk menyimpan file
        $filePath = Storage::disk('public')->path($qrTarget);

        // Hasilkan QR Code ke file
        $writer->writeFile($qrContent, $filePath);

        // Periksa apakah file berhasil dibuat
        if (Storage::disk('public')->exists($qrTarget)) {
            return $qrName; // Kembalikan nama file QR
        } else {
            return "0"; // Kembalikan "0" jika gagal
        }
    }


    public function render()
    {
        return view('livewire.aset-form');
    }
}
