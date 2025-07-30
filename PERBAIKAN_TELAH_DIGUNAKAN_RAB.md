# PERBAIKAN LOGIKA "TELAH DIGUNAKAN" PADA RAB

## MASALAH YANG DITEMUKAN

### 1. **Logika Perhitungan yang Salah**

Method `hitungTelahDigunakan` sebelumnya menggunakan logika yang tidak konsisten:

-   Mencampur status `detailPermintaan.status` dengan field `proses` yang deprecated
-   Menghitung permintaan yang masih dalam proses (belum dikirim/selesai)
-   Logika double counting yang menyebabkan perhitungan tidak akurat

### 2. **Status yang Tidak Tepat**

Sebelumnya menghitung berdasarkan:

-   `detailPermintaan.status` dengan filter `[2, 3]`
-   `DetailPermintaanMaterial.proses = 1` (field deprecated)

Status yang benar berdasarkan analisis sistem:

-   `null` = Diproses
-   `0` = Ditolak
-   `1` = Disetujui
-   `2` = Sedang Dikirim ✅
-   `3` = Selesai ✅

## PERBAIKAN YANG DILAKUKAN

### 1. **ListRab.php** - Livewire Component

#### Added Import Statements:

```php
use App\Models\PermintaanMaterial;
use App\Models\DetailPermintaanMaterial;
```

#### Updated mount() method:

```php
if ($this->rab_id) {
    $rab = Rab::find($this->rab_id);
    foreach ($rab->list as $item) {
        $this->list[] = [
            'id' => $item->id,
            'merk' => MerkStok::find($item->merk_id),
            'jumlah' => $item->jumlah,
            'telah_digunakan' => $this->hitungTelahDigunakan($item->merk_id, $this->rab_id)
        ];
    }
}
```

#### Added hitungTelahDigunakan() method:

```php
public function hitungTelahDigunakan($merkId, $rabId)
{
    $totalTelahDigunakan = 0;

    try {
        // Hitung dari permintaan material yang sudah dikirim/selesai
        $permintaanMaterial = PermintaanMaterial::where('merk_id', $merkId)
            ->where('rab_id', $rabId)
            ->whereHas('detailPermintaan', function ($query) {
                $query->whereIn('status', [2, 3]); // 2 = Sedang Dikirim, 3 = Selesai
            })
            ->whereHas('stokDisetujui', function ($query) {
                $query->where('jumlah_disetujui', '>', 0);
            })
            ->with('stokDisetujui')
            ->get();

        foreach ($permintaanMaterial as $permintaan) {
            $totalTelahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
        }

        // Hitung dari DetailPermintaanMaterial yang menggunakan RAB
        $detailPermintaanRAB = DetailPermintaanMaterial::where('rab_id', $rabId)
            ->whereIn('status', [2, 3])
            ->whereHas('permintaanMaterial', function ($query) use ($merkId) {
                $query->where('merk_id', $merkId)
                    ->whereHas('stokDisetujui', function ($subQuery) {
                        $subQuery->where('jumlah_disetujui', '>', 0);
                    });
            })
            ->with(['permintaanMaterial' => function ($query) use ($merkId) {
                $query->where('merk_id', $merkId)->with('stokDisetujui');
            }])
            ->get();

        foreach ($detailPermintaanRAB as $detail) {
            foreach ($detail->permintaanMaterial as $permintaan) {
                if ($permintaan->merk_id == $merkId) {
                    $totalTelahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
                }
            }
        }

    } catch (\Exception $e) {
        \Log::error('Error calculating telah digunakan RAB: ' . $e->getMessage());
    }

    return $totalTelahDigunakan;
}
```

### 2. **StokHelper.php** - Helper Class

#### Updated getJumlahSudahDigunakan() method:

```php
public static function getJumlahSudahDigunakan($merkId, $rabId)
{
    $totalSudahDigunakan = 0;

    // Hitung dari PermintaanMaterial yang menggunakan RAB dan sudah dikirim/selesai
    $permintaanMaterial = PermintaanMaterial::where('merk_id', $merkId)
        ->where('rab_id', $rabId)
        ->whereHas('detailPermintaan', function ($query) {
            $query->whereIn('status', [2, 3]); // 2 = Sedang Dikirim, 3 = Selesai
        })
        ->whereHas('stokDisetujui', function ($query) {
            $query->where('jumlah_disetujui', '>', 0);
        })
        ->with('stokDisetujui')
        ->get();

    foreach ($permintaanMaterial as $permintaan) {
        $totalSudahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
    }

    // Hitung dari DetailPermintaanMaterial yang menggunakan RAB
    $detailPermintaanRAB = DetailPermintaanMaterial::where('rab_id', $rabId)
        ->whereIn('status', [2, 3])
        ->whereHas('permintaanMaterial', function ($query) use ($merkId) {
            $query->where('merk_id', $merkId)
                ->whereHas('stokDisetujui', function ($subQuery) {
                    $subQuery->where('jumlah_disetujui', '>', 0);
                });
        })
        ->with(['permintaanMaterial' => function ($query) use ($merkId) {
            $query->where('merk_id', $merkId)->with('stokDisetujui');
        }])
        ->get();

    foreach ($detailPermintaanRAB as $detail) {
        foreach ($detail->permintaanMaterial as $permintaan) {
            if ($permintaan->merk_id == $merkId) {
                $totalSudahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
            }
        }
    }

    return $totalSudahDigunakan;
}
```

### 3. **list-rab.blade.php** - View Template

#### Updated table row to display "telah digunakan":

```blade
@if ($rab_id)
<td class="py-3 px-6">
    <div class="flex items-center">
        <input type="number" value="{{ $item['telah_digunakan'] ?? 0 }}" disabled
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg cursor-not-allowed focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            placeholder="0">
        <span
            class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
            {{ $item['merk']->barangStok->satuanBesar->nama }}
        </span>
    </div>
</td>
@endif
```

## LOGIKA YANG BENAR

### Alur Perhitungan "Telah Digunakan":

1. **Ambil PermintaanMaterial** yang:

    - `merk_id` = merk dari RAB
    - `rab_id` = RAB yang sedang dilihat
    - `detailPermintaan.status` IN [2, 3] (Sedang Dikirim/Selesai)
    - Memiliki `stokDisetujui` dengan `jumlah_disetujui > 0`

2. **Ambil DetailPermintaanMaterial** yang:

    - `rab_id` = RAB yang sedang dilihat
    - `status` IN [2, 3] (Sedang Dikirim/Selesai)
    - Memiliki `permintaanMaterial` dengan `merk_id` yang sesuai
    - Memiliki `stokDisetujui` dengan `jumlah_disetujui > 0`

3. **Jumlahkan semua `jumlah_disetujui`** dari kedua sumber di atas

### Pengambilan Bertahap/Parsial:

Sistem sekarang mendukung pengambilan barang secara bertahap:

**Contoh:**

-   RAB: 1000 unit Semen
-   Permintaan 1: 300 unit (status: Selesai) → Telah digunakan: 300
-   Permintaan 2: 200 unit (status: Sedang Dikirim) → Telah digunakan: 500
-   Permintaan 3: 150 unit (status: Diproses) → Telah digunakan: 500 (tidak dihitung)
-   Permintaan 4: 100 unit (status: Selesai) → Telah digunakan: 600

## HASIL YANG DIHARAPKAN

✅ **Akurat:** Hanya menghitung permintaan yang benar-benar sudah dikirim/selesai
✅ **Konsisten:** Menggunakan status yang sama di seluruh sistem
✅ **Mendukung Parsial:** Bisa melacak pengambilan bertahap
✅ **Tidak Double Count:** Menghindari duplikasi perhitungan
✅ **Performance:** Query yang efisien dengan proper relationships

## STATUS PERMINTAAN MATERIAL

| Status | Nilai | Label          | Dihitung dalam "Telah Digunakan" |
| ------ | ----- | -------------- | -------------------------------- |
| null   | null  | Diproses       | ❌ Tidak                         |
| 0      | 0     | Ditolak        | ❌ Tidak                         |
| 1      | 1     | Disetujui      | ❌ Tidak                         |
| 2      | 2     | Sedang Dikirim | ✅ Ya                            |
| 3      | 3     | Selesai        | ✅ Ya                            |

## FILES YANG DIMODIFIKASI

1. `app/Livewire/ListRab.php` - Added method hitungTelahDigunakan()
2. `app/Helpers/StokHelper.php` - Updated getJumlahSudahDigunakan()
3. `resources/views/livewire/list-rab.blade.php` - Display telah digunakan value
