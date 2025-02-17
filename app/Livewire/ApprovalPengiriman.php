<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\Persetujuan;
use Livewire\Attributes\On;
use App\Models\PengirimanStok;
use App\Models\OpsiPersetujuan;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPengirimanStok;
use Illuminate\Support\Facades\Notification;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ApprovalPengiriman extends Component
{

    use WithFileUploads;
    public $pengiriman;
    public $penanggungjawab;
    public $penulis;
    public $user;
    public $date;
    public $pjList;
    public $lastPj;
    public $ppkList;
    public $lastPpk;
    public $penerimaList;
    public $lastPenerima;
    public $pemeriksaList;
    public $lastPemeriksa;
    public $pptkList;
    public $lastPptk;
    public $listApproval;
    public $roles;
    public $showButton;
    public $status;
    public $isLastUser;

    public $newbapfiles = []; // To hold multiple uploaded files
    public $bapfiles = []; // To hold multiple uploaded files
    public $newApprovalFiles = []; // To hold multiple uploaded files
    public $approvalFiles = []; // To hold multiple uploaded files
    public $files = [], $arrayfiles; // To hold multiple uploaded files

    protected $listeners = ['eventName' => 'statusAppPenerima'], $receivedData;

    public function handleEvent($data)
    {
        // Handle the event and update the component's state
        $this->receivedData = $data;
    }

    public function updatedNewApprovalFiles()
    {
        // Validate each uploaded file
        $this->validate([
            'approvalFiles.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
        ]);
        foreach ($this->newApprovalFiles as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->approvalFiles[] = $file;
        }

        $this->dispatch('file_approval', count: count($this->approvalFiles));
        // Clear the newAttachments to make ready for next files
        $this->reset('newApprovalFiles');
    }

    public function updatednewbapfiles()
    {
        // Validate each uploaded file
        $this->validate([
            'bapfiles.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
        ]);
        foreach ($this->newbapfiles as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->bapfiles[] = $file;
        }

        $this->dispatch('bap_file', count: count($this->bapfiles));
        // Clear the newAttachments to make ready for next files
        $this->reset('newbapfiles');
    }

    public function mount()
    {
        if ($this->pengiriman->persetujuan->where('file')) {
            // Fetch files where the status is true and the file exists
            $this->files = $this->pengiriman->persetujuan->filter(function ($persetujuan) {
                return $persetujuan->file !== null;
            })->pluck('file');
        } else {
            $this->files = [];
        }

        if ($this->pengiriman->bapfile->where('file')) {
            // Fetch files where the status is true and the file exists
            $this->arrayfiles = $this->pengiriman->bapfile->filter(function ($persetujuan) {
                return $persetujuan->file !== null;
            })->pluck('file');
        } else {
            $this->arrayfiles = [];
        }

        $this->user = Auth::user();
        $this->roles = Auth::user()->roles->pluck('name')->first();
        $this->penulis = $this->pengiriman->user;
        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);

        $pj = User::role('Penanggung Jawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $indexPj = $pj->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPj = $indexPj === $pj->count() - 1;
        $this->pjList = $pj;

        $ppk = User::role('Pejabat Pembuat Komitmen')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPpk = $ppk->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPpk = $indexPpk === $ppk->count() - 1; // Check if current user is the last user
        $this->ppkList = $ppk;
        // dd(PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id'));
        $penerima = User::with([
            'pengirimanStok' => function ($query) {
                $query->where('detail_pengiriman_id', $this->pengiriman->id);
            }
        ])
            ->role('Penerima Barang') // Pastikan metode `role` didefinisikan jika menggunakan Spatie Laravel Permission
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->whereHas('lokasiStok', function ($query) {
                $query->whereIn(
                    'lokasi_id',
                    PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id')
                );
            })
            ->get();
        $penerima = User::with([
            'pengirimanStok' => function ($query) {
                $query->where('detail_pengiriman_id', $this->pengiriman->id);
            }
        ])
            ->role('Penerima Barang') // Pastikan metode `role` didefinisikan jika menggunakan Spatie Laravel Permission
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->whereHas('lokasiStok', function ($query) {
                $query->whereIn(
                    'lokasi_id',
                    PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id')
                );
            })
            ->get();
        $penerima = User::with([
            'pengirimanStok' => function ($query) {
                $query->where('detail_pengiriman_id', $this->pengiriman->id);
            }
        ])
            ->role('Penerima Barang') // Pastikan metode `role` didefinisikan jika menggunakan Spatie Laravel Permission
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->whereHas('lokasiStok', function ($query) {
                $query->whereIn(
                    'lokasi_id',
                    PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id')
                );
            })
            ->get();

        $indexPenerima = $penerima->search(function ($user) {
            return $user->id == Auth::id();
        });

        $this->lastPenerima = $indexPenerima === $penerima->count() - 1; // Check if current user is the last user
        $this->penerimaList = $this->checkApprovePB();

        $optionLastPemeriksa = $this->pengiriman->opsiPersetujuan->userPenyelesai;

        $pemeriksa = User::role('Pemeriksa Barang')->whereHas('unitKerja', function ($subQuery) {
            $subQuery->where('unit_id', $this->pengiriman->user->unit_id);
        })->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        // Cari dan hapus $optionLastPemeriksa dari collection $pemeriksa jika ada
        $filteredPemeriksa = $pemeriksa->reject(function ($user) use ($optionLastPemeriksa) {
            return $user->id === $optionLastPemeriksa->id;
        });

        // Tambahkan $optionLastPemeriksa kembali ke akhir collection
        $pemeriksa = $filteredPemeriksa->push($optionLastPemeriksa)->values();





        $indexPemeriksa = $pemeriksa->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPemeriksa = $indexPemeriksa === $pemeriksa->count() - 1; // Check if current user is the last user
        $this->pemeriksaList = $pemeriksa;


        $pptk = User::role('Pejabat Pelaksana Teknis Kegiatan')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPptk = $pptk->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPptk = $indexPptk === $pptk->count() - 1; // Check if current user is the last user
        $this->pptkList = $pptk;

        // Menentukan urutan approval yang benar
        $this->listApproval = $this->penerimaList
            ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList)
            ->count();

        // Menggabungkan list approval sesuai urutan yang benar
        $allApproval = $this->penerimaList
            ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList);
        $index = $allApproval->search(function ($user) {
            return $user->id == Auth::id();
        });
        if ($index === 0) {
            $currentUser = $allApproval[$index];
            $this->showButton = !$currentUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists() && !$this->pengiriman->pengirimanStok->whereNull('status_lokasi')->count() &&
                (
                    !$currentUser->hasRole('Pemeriksa Barang') ? 1 : ($this->pengiriman->pengirimanStok->map(function ($pengiriman) {
                        return $pengiriman->stokDiterima->where('user_id', $pengiriman);
                    }))
                );
        } else {
            $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
            $currentUser = $allApproval[$index];
            // dump(

            // );
            $this->showButton =
                $previousUser && !$currentUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists() &&
                $previousUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists() && !$this->pengiriman->pengirimanStok->whereNull('status_lokasi')->count() &&
                (
                    !$currentUser->hasRole('Pemeriksa Barang') ? 1 : $this->pengiriman->pengirimanStok->map(function ($pengiriman) use ($currentUser) {
                        return $pengiriman->stokDiterima->where('user_id', $currentUser->id)->where('pengiriman_id', $pengiriman->id);
                    })->flatten(1)->count() ==
                    $this->pengiriman->pengirimanStok->count()
                );
        }

        // $this->indikatorPenerima = $this->checkApprovPenerimaBarang($this->pengiriman->id, Auth::id()); 

        //$this->receivedData ??

        $this->checkPreviousApproval = $this->CheckApproval();
        $this->CheckCurrentApproval = $this->CheckCurrentApproval();
        // $this->indikatorPenerima = $this->checkApprovPenerimaBarang($this->pengiriman->id, Auth::id()); 
    }

    public $indikatorPenerima, $checkPreviousApproval, $CheckCurrentApproval;

    #[On('itemApproved')]
    public function refreshButton()
    {

        $this->mount();
    }
    public function checkApprovePB()
    {
        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);
        $data = User::with(['pengirimanStok' => function ($query) {
            $query->where('detail_pengiriman_id', $this->pengiriman->id);
        }])
            ->role('Penerima Barang') // Pastikan metode `role` didefinisikan jika menggunakan Spatie Laravel Permission
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->whereHas('lokasiStok', function ($query) {
                $query->whereIn(
                    'lokasi_id',
                    PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id')
                );
            })
            ->get();


        $processedData = $data->map(function ($item) {

            $cekApprove = false;

            $item->pengirimanStok->map(function ($pengiriman) use (&$cekApprove) {
                $cekApprove = true;
                // if (empty($pengiriman->bagian_id) || empty($pengiriman->posisi_id) || empty($pengiriman->img)) {
                if (!$pengiriman->status_lokasi) {
                    $cekApprove = false;  // If any condition is met, set $cekApprove to true
                }
                // Assign the $cekApprove flag to each pengirimanStok
                $pengiriman->cekpeng = $cekApprove;
            });

            $cekApprove = $item->pengirimanStok->whereNull('status_lokasi')->count() == 0;
            // Assign the final value of $cekApprove to the parent item
            $item->cekApprove = $cekApprove;
            if ($cekApprove) {
                $this->pengiriman->persetujuan()->firstOrCreate([
                    'detail_pengiriman_id' => $this->pengiriman->id,
                    'user_id' => $item->id,
                    'status' => true,
                ]);
            }

            // Return the modified item
            return $item;
        });

        // Return the processed data
        return $processedData;
    }

    #[On('checkApproval')]
    public function refreshAproval()
    {
        $this->mount();
    }


    public function checkApprovPenerimaBarang($id, $user_id)
    {

        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);

        $data = PengirimanStok::where('detail_pengiriman_id', $id)->whereHas('lokasiStok', function ($query) use ($user_id) {
            $query->whereHas('user', fn($q) => $q->where('id', $user_id));
        })->where(
            function ($query) {
                $query->where('img', null)->orWhere('bagian_id', null)->orWhere('posisi_id', null);
            }
        )->get();

        if ($data->isEmpty()) {
            $result = 1;
        } else {
            $result = 0;
        }

        return $result;
    }

    public function approveConfirmed()
    {
        $list = $this->penerimaList
            ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList);
        // dd($list);
        // if ($this->lastPj || $this->lastPpk || $this->lastPptk || $this->lastPenerima || $this->lastPemeriksa) {
        if ($this->lastPj || $this->lastPpk || $this->lastPptk || $this->lastPenerima || $this->lastPemeriksa) {
            foreach ($this->approvalFiles as $file) {
                $path = str_replace('dokumen-persetujuan-pengiriman/', '', $file->storeAs('dokumen-persetujuan-pengiriman', $file->getClientOriginalName(), 'public'));
                $this->pengiriman->persetujuan()->create([
                    'detail_pengiriman_id' => $this->pengiriman->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                    'file' => $path
                ]);
            }
            // $list = $this->pengiriman->persetujuan;
            // $filteredList = $list->filter(function ($approval) {
            //     return $approval->status;
            // })->unique('user_id');
            // dd($filteredList->count(), $this->listApproval);
            foreach ($this->bapfiles as $file) {
                $path = str_replace('dokumen-persetujuan-pengiriman/bap/', '', $file->storeAs('dokumen-persetujuan-pengiriman/bap', $file->getClientOriginalName(), 'public'));
                $this->pengiriman->bapfile()->create([

                    'user_id' => Auth::id(),
                    'status' => true,
                    'file' => $path,
                    'type' => 'bap'
                ]);
            }
            $list = $this->pengiriman->persetujuan;
            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
            // dd($filteredList->count(), $this->listApproval$this->listApproval);
            if ($filteredList->count() == $this->listApproval) {
                $this->pengiriman->status = true;
                $this->pengiriman->save();
                $pengirimanItems = $this->pengiriman->pengirimanStok;
                foreach ($pengirimanItems as $pengiriman) {
                    $stok = Stok::firstOrCreate(
                        [
                            'merk_id' => $pengiriman->merk_id,
                            'lokasi_id' => $pengiriman->lokasi_id,
                            'bagian_id' => $pengiriman->bagian_id,
                            'posisi_id' => $pengiriman->posisi_id,
                        ],
                        ['jumlah' => 0]  // Atur stok awal jika belum ada
                    );

                    $stok->jumlah += $pengiriman->jumlah_diterima;
                    $stok->save();
                }
            }
        } else {
            foreach ($this->approvalFiles as $file) {
                $path = str_replace('dokumen-persetujuan-pengiriman/', '', $file->storeAs('dokumen-persetujuan-pengiriman', $file->getClientOriginalName(), 'public'));
                $this->pengiriman->persetujuan()->create([
                    'detail_pengiriman_id' => $this->pengiriman->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                    'file' => $path
                ]);
            }


            $list = $this->pengiriman->persetujuan;

            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
            if ($filteredList->count() == $this->listApproval) {
                $this->pengiriman->status = true;
                $this->pengiriman->save();
                $pengirimanItems = $this->pengiriman->pengirimanStok;
                foreach ($pengirimanItems as $pengiriman) {
                    $stok = Stok::updateOrCreate(
                        [
                            'merk_id' => $pengiriman->merk_id,
                            'lokasi_id' => $pengiriman->lokasi_id,
                            'bagian_id' => $pengiriman->bagian_id,
                            'posisi_id' => $pengiriman->posisi_id,
                        ],
                        ['jumlah' => 0]  // Atur stok awal jika belum ada
                    );

                    $stok->jumlah += $pengiriman->jumlah;
                    $stok->save();
                }
            }
        }

        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => true,
        ]);
        $allApproval = $this->penerimaList
            ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList);
        $index = $allApproval->search(function ($user) {
            return $user->id == Auth::id();
        });

        $detailPengiriman = $this->pengiriman;
        $user = $allApproval[$index + 1];

        $message = 'Pengirimaan <span class="font-bold">' . $detailPengiriman->kode_pengiriman_stok . '</span> membutuhkan persetujuan Anda.';
        Notification::send($user, new UserNotification($message, "/pengiriman-stok/{$detailPengiriman->id}"));



        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public $indikatorPPTK;

    public function ApprovePPTK()
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => true
        ]);
        $this->pengiriman->save();
        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);
        // return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);
    }

    // public function CheckApproval()
    // {
    //     $urutanApproval = collect(['Penerima Barang', 'Pemeriksa Barang', 'Pejabat Pelaksana Teknis Kegiatan', 'Pejabat Pembuat Komitmen']);
    //     // return $this->pengiriman->persetujuan()->get();
    //     $index = $urutanApproval->search(function ($item) {
    //         return $item === $this->roles;
    //     });
    //     $previousElement = $urutanApproval->filter(function ($item, $key) use ($index) {
    //         return $key === $index - 1;
    //     })->first();

    //     // return $previousElement;
    //     // return $this->pengiriman->whereHas(
    //     //     'persetujuan',
    //     //     fn($query) => $query->where('status', 1)->whereHas(
    //     //         'user',
    //     //         fn($query1) => $query1->role($previousElement)
    //     //     )
    //     // )->get();

    //     return PersetujuanPengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->where('status', 1)->whereHas(
    //         'user',
    //         fn($query1) => $query1->role($previousElement)
    //     )->get();
    // }

    public function CheckCurrentApproval()
    {
        $currentRole = $this->roles;

        // return $previousElement;
        // return $this->pengiriman->whereHas(
        //     'persetujuan',
        //     fn($query) => $query->where('status', 1)->whereHas(
        //         'user',
        //         fn($query1) => $query1->role($currentRole)
        //     )
        // )->get();
        return PersetujuanPengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->where('status', 1)->whereHas(
            'user',
            fn($query1) => $query1->role($currentRole)
        )->get();
    }
    public function CheckApproval()
    {
        $urutanApproval = collect(['Pemeriksa Barang', 'Pejabat Pelaksana Teknis Kegiatan', 'Pejabat Pembuat Komitmen']);
        // return $this->pengiriman->persetujuan()->get();
        $index = $urutanApproval->search(function ($item) {
            return $item === $this->roles;
        });
        $previousElement = $urutanApproval->filter(function ($item, $key) use ($index) {
            return $key === $index - 1;
        })->first();

        // return $previousElement;
        // return $this->pengiriman->whereHas(
        //     'persetujuan',
        //     fn($query) => $query->where('status', 1)->whereHas(
        //         'user',
        //         fn($query1) => $query1->role($previousElement)
        //     )
        // )->get();

        return PersetujuanPengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->where('status', 1)->whereHas(
            'user',
            fn($query1) => $query1->role($previousElement)
        )->get();
    }

    // public function CheckCurrentApproval()
    // {
    //     $currentRole = $this->roles;

    //     // return $previousElement;
    //     // return $this->pengiriman->whereHas(
    //     //     'persetujuan',
    //     //     fn($query) => $query->where('status', 1)->whereHas(
    //     //         'user',
    //     //         fn($query1) => $query1->role($currentRole)
    //     //     )
    //     // )->get();
    //     return PersetujuanPengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->where('status', 1)->whereHas(
    //         'user',
    //         fn($query1) => $query1->role($currentRole)
    //     )->get();
    // }

    public function ApprovePPKandFinish()
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => true
        ]);


        if ($this->pengiriman->save()) {
            $this->pengiriman->status = true;
            $this->pengiriman->save();
            $pengirimanItems = $this->pengiriman->pengirimanStok;
            foreach ($pengirimanItems as $pengiriman) {
                $stok = Stok::UpdateOrCreate(
                    [
                        'merk_id' => $pengiriman->merk_id,
                        'lokasi_id' => $pengiriman->lokasi_id,
                        'bagian_id' => $pengiriman->bagian_id,
                        'posisi_id' => $pengiriman->posisi_id,
                    ],
                    ['jumlah' => 0]  // Atur stok awal jika belum ada
                );

                $stok->jumlah += $pengiriman->jumlah_diterima;
                $stok->save();
            }
        }
        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);
    }

    public function rejectConfirmed($reason)
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => false,
            'keterangan' => $reason
        ]);
        $this->pengiriman->status = false;
        $this->pengiriman->save();

        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function removeApprovalFile($index)
    {
        // Remove file from the list
        if (isset($this->approvalFiles[$index])) {
            // Delete persisted file if needed
            if (!($this->approvalFiles[$index] instanceof \Illuminate\Http\UploadedFile)) {
                Storage::delete('dokumen-persetujuan-pengiriman/' . $this->approvalFiles[$index]);
            }
            unset($this->approvalFiles[$index]);
            $this->approvalFiles = array_values($this->approvalFiles); // Reindex array
            $this->dispatch('file_approval', count: count($this->approvalFiles));
        }
    }

    public function removeBapFile($index)
    {
        // Remove file from the list
        if (isset($this->bapfiles[$index])) {
            // Delete persisted file if needed
            if (!($this->bapfiles[$index] instanceof \Illuminate\Http\UploadedFile)) {
                Storage::delete('dokumen-persetujuan-pengiriman/' . $this->bapfiles[$index]);
            }
            unset($this->bapfiles[$index]);
            $this->bapfiles = array_values($this->bapfiles); // Reindex array
            $this->dispatch('bap_file', count: count($this->bapfiles));
        }
    }


    public function render()
    {
        return view('livewire.approval-pengiriman');
    }
}
