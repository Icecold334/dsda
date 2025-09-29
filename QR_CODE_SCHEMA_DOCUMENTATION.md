# QR Code Schema Implementation - Material Requests

## Overview

Sistem QR Code untuk permintaan material telah diimplementasikan dengan skema yang berbeda berdasarkan status permintaan.

## QR Code Flow

### 1. **QR Code Generation**

-   **Trigger**: Saat Kepala Subbagian Tata Usaha menyetujui permintaan
-   **Location**: `app/Livewire/ApprovalMaterial.php` line 290-308
-   **URL Pattern**: `{domain}/qr/material/3/{kode_permintaan}`
-   **File Storage**: `storage/app/public/qr_permintaan_material/{kode_permintaan}.png`

### 2. **Status-Based Routing**

#### ✅ **Status 1 - Disetujui**

```
QR Code Scan → Download PDF Form (SPB + SPPB)
Route: /qr/material/{user_id}/{kode}
Action: downloadGabunganPdf($permintaan->id)
Result: File SPB_SPPB.pdf langsung di-download
```

#### ✅ **Status 2 - Sedang Dikirim**

```
QR Code Scan → Show Permintaan Page with Info Alert
Route: /qr/material/{user_id}/{kode} → redirect to /permintaan/material/{id}
Alert: INFO - "Permintaan sedang dalam proses pengiriman."
```

#### ✅ **Status 3 - Selesai**

```
QR Code Scan → Show Permintaan Page with Success Alert
Route: /qr/material/{user_id}/{kode} → redirect to /permintaan/material/{id}
Alert: SUCCESS - "Permintaan telah selesai diproses."
```

#### ✅ **Status Lain (null, 0)**

```
QR Code Scan → Show Permintaan Page with Warning Alert
Route: /qr/material/{user_id}/{kode} → redirect to /permintaan/material/{id}
Alert: WARNING - "Permintaan belum dapat diakses. Status: {status_teks}"
```

## Implementation Details

### Routes (web.php)

```php
// Primary QR Scan Route
Route::get('/qr/material/{user_id}/{kode}', function ($user_id, $kode) {
    // Status-based logic here
})->name('scan_material');

// Show Permintaan Route
Route::get('permintaan/{tipe}/{id}', [PermintaanStokController::class, 'show'])
    ->name('showPermintaan');

// Alternative QR Download Route
Route::get('/material/{id}/qrDownload', function ($id) {
    // Same logic as scan_material
});
```

### Status Mapping

```php
$statusMap = [
    null => ['label' => 'Diproses', 'color' => 'warning'],
    0 => ['label' => 'Ditolak', 'color' => 'danger'],
    1 => ['label' => 'Disetujui', 'color' => 'success'],
    2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
    3 => ['label' => 'Selesai', 'color' => 'primary'],
];
```

### Alert Handling (ShowPermintaanMaterial.php)

```php
public function mount()
{
    // Check for session alert
    if (session('alert')) {
        $this->alert = session('alert');
    }

    // Add status_teks if not exists
    if (!isset($this->permintaan->status_teks)) {
        $statusMap = [/* status mapping */];
        $this->permintaan->status_teks = $statusMap[$this->permintaan->status]['label'] ?? 'Tidak diketahui';
    }
}

public function dismissAlert()
{
    $this->alert = null;
}
```

## Testing Scenarios

### Scenario 1: Approved Request (Status 1)

1. Scan QR Code
2. Should automatically download SPB_SPPB.pdf
3. No redirect to page

### Scenario 2: Shipping Request (Status 2)

1. Scan QR Code
2. Redirect to /permintaan/material/{id}
3. Show blue info alert: "Permintaan sedang dalam proses pengiriman."

### Scenario 3: Completed Request (Status 3)

1. Scan QR Code
2. Redirect to /permintaan/material/{id}
3. Show green success alert: "Permintaan telah selesai diproses."

### Scenario 4: Pending/Rejected Request (Status null/0)

1. Scan QR Code
2. Redirect to /permintaan/material/{id}
3. Show yellow warning alert: "Permintaan belum dapat diakses. Status: Diproses/Ditolak"

## Files Modified

1. **routes/web.php**

    - Added status mapping to QR routes
    - Updated alert messages with proper status text

2. **app/Livewire/ShowPermintaanMaterial.php**

    - Enhanced mount() method for alert handling
    - Added status_teks mapping
    - dismissAlert() method already exists

3. **app/Livewire/ApprovalMaterial.php**
    - QR Code generation already implemented correctly

## Security Features

-   Guest user (ID: 3) used for QR access
-   No authentication required for QR scanning
-   Direct PDF download for approved requests
-   Proper error handling for invalid codes

## Benefits

✅ **User-Friendly**: Different actions based on request status  
✅ **Secure**: No sensitive data exposed in QR codes  
✅ **Efficient**: Direct PDF download for approved requests  
✅ **Informative**: Clear status messages for users  
✅ **Robust**: Proper error handling and fallbacks

---

**Author**: GitHub Copilot  
**Date**: August 1, 2025  
**Version**: 1.0
