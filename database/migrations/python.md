# 🧾 Catatan Pembersihan SQL Dump (`dumpp-s.sql`)

## 🎯 Tujuan

Membersihkan file dump SQL lama yang berisi data dari berbagai tabel, dengan fokus hanya pada dua hal:

1. **Perbaiki kolom jumlah** di query `INSERT INTO transaksi_stok`
    - Hapus tanda kutip `'...'` di nilai numerik seperti `'327.677.0'` → `327.677`
2. **Hapus fungsi Oracle `unistr(...)`** di seluruh query
    - Ganti isi string `unistr('...')` jadi string biasa `'...'`
    - Ubah `\u000a` jadi newline literal `\n`
    - Perbaiki kutip ganda SQL (`''` jadi `'`)

Semua query lain (selain dua kondisi di atas) **tidak disentuh sama sekali**.

---

## ⚙️ Logika Script

1. Baca file `dumpp-s.sql` baris per baris.
2. Jika baris diawali dengan `INSERT INTO transaksi_stok`, perbaiki kolom jumlah (index ke-20).
3. Jika baris mengandung `unistr(`, ubah jadi string normal.
4. Semua baris lain disalin langsung tanpa perubahan.
5. Simpan hasil akhir ke `dumpp-s-fixed.sql`.

---

## 💻 Kode Python Final

Simpan ini sebagai `fix_sql_dump.py`:

```python
import re

input_file = "dumpp-s.sql"
output_file = "dumpp-s-fixed.sql"

def fix_jumlah_if_transaksi_stok(line: str) -> str:
    """Perbaiki kolom jumlah di baris INSERT INTO transaksi_stok"""
    if not line.lower().startswith("insert into transaksi_stok"):
        return line

    # Ambil isi VALUES(...) dengan menghitung tanda kurung agar aman
    start = line.lower().find("values(")
    if start == -1:
        return line
    start += len("values(")
    depth = 1
    i = start
    while i < len(line) and depth > 0:
        if line[i] == "(":
            depth += 1
        elif line[i] == ")":
            depth -= 1
        i += 1
    if depth != 0:
        return line  # tanda kurung tidak seimbang
    values_str = line[start:i-1]

    # Pisah nilai berdasarkan koma di luar tanda kutip
    parts = re.findall(r"(?:'[^']*'|[^,]+)", values_str)

    # Kolom ke-21 (index 20) = jumlah
    if len(parts) > 20:
        val_20 = parts[20].strip()
        if re.fullmatch(r"'[\d\.]+'", val_20):
            num = val_20.strip("'")
            if num.endswith(".0"):
                num = num[:-2]
            if re.match(r"^\d+\.\d+\.0$", num):
                num = ".".join(num.split(".")[:-1])
            parts[20] = num
            print(f"[transaksi_stok] fixed jumlah: {val_20} → {num}")

    return f"INSERT INTO transaksi_stok VALUES({','.join(parts)});\n"


def fix_unistr_if_exists(line: str) -> str:
    """Hapus fungsi Oracle UNISTR di baris apa pun"""
    if "unistr(" not in line.lower():
        return line

    def repl(m):
        # Ambil isi string dan ubah escape SQL '' jadi '
        inner = m.group(1).replace("''", "'")
        # Ganti unicode escape \u000a -> newline
        inner = inner.replace("\\u000a", "\\n")
        return f"'{inner}'"

    fixed = re.sub(
        r"unistr\('((?:[^']|'')*)'\)",
        repl,
        line,
        flags=re.IGNORECASE
    )

    print(f"[unistr] cleaned one line")
    return fixed


# --- Eksekusi utama ---
with open(input_file, "r", encoding="utf-8") as f:
    lines = f.readlines()

fixed_lines = []

for line in lines:
    if line.lower().startswith("insert into transaksi_stok"):
        line = fix_jumlah_if_transaksi_stok(line)
    if "unistr(" in line.lower():
        line = fix_unistr_if_exists(line)
    fixed_lines.append(line)

with open(output_file, "w", encoding="utf-8") as f:
    f.writelines(fixed_lines)

print("✅ Selesai! Hanya baris transaksi_stok dan UNISTR yang diperbaiki, sisanya tetap utuh.")
```
