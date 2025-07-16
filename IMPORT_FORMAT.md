# Format Import untuk KantorDataImport

## Format CSV/Excel yang Diperlukan

Format file CSV/Excel harus memiliki header (baris pertama) dengan kolom-kolom berikut:

### Kolom Wajib:
- `nopen` - Nomor Identifikasi Kantor (unique)
- `kantor` - Nama Kantor
- `kab_kota` - Kabupaten/Kota
- `alokasi_kpm` - Alokasi KPM (mendukung format Indonesia: 12.657)
- `alokasi_jml_uang` - Alokasi Jumlah Uang (mendukung format Indonesia: 9.831.300.000)

### Kolom Opsional (untuk Sufix):
- `nama_sufix` - Nama Sufix

### Kolom Opsional (untuk SubSufix):
- `alokasi` - Alokasi SubSufix (mendukung format Indonesia)
- `alokasi_biaya` - Alokasi Biaya SubSufix (mendukung format Indonesia)
- `realisasi` - Realisasi SubSufix (mendukung format Indonesia)
- `realisasi_biaya` - Realisasi Biaya SubSufix (mendukung format Indonesia)
- `gagal_bayar_tolak` - Gagal Bayar Tolak (mendukung format Indonesia)
- `sisa_aktif` - Sisa Aktif (mendukung format Indonesia, "-" akan diubah menjadi 0)
- `sisa_biaya` - Sisa Biaya (mendukung format Indonesia)

## Format Angka yang Didukung

Import ini mendukung format angka Indonesia:
- **Ribuan**: `12.657` (titik sebagai pemisah ribuan)
- **Jutaan**: `9.831.300.000` (titik sebagai pemisah ribuan)
- **Nilai kosong**: `-` akan diubah menjadi `0`
- **Null/kosong**: akan diubah menjadi `0`

## Contoh Data yang Benar:

```csv
nopen,kantor,kab_kota,alokasi_kpm,alokasi_jml_uang,nama_sufix,alokasi,alokasi_biaya,realisasi,realisasi_biaya,gagal_bayar_tolak,sisa_aktif,sisa_biaya
53400,Banjarnegara-53400,Banjarnegara,12.657,9.831.300.000,Sufix A,854,1.125.150.000,849,1.119.150.000,5,-,6.000.000
55700,Bantul-55700,Bantul,6.458,5.105.625.000,Sufix B,2.254,1.352.400.000,2.229,1.337.400.000,25,-,15.000.000
```

## Cara Kerja Import:

1. **Kantor**: Dibuat/diupdate berdasarkan `nopen` (unique key)
2. **Sufix**: Dibuat berdasarkan kombinasi `nama_sufix` + `kantor_id` (unique)
3. **SubSufix**: Dibuat baru untuk setiap baris yang memiliki data SubSufix
4. **Total**: Dibuat SATU per Kantor (agregasi dari semua SubSufixes dalam kantor tersebut)
5. **Format Angka**: Otomatis mengkonversi format Indonesia ke format database

## Catatan Penting:

- ✅ **Format Indonesia Didukung**: Angka dengan titik sebagai pemisah ribuan (12.657, 9.831.300.000)
- ✅ **Nilai "-" Didukung**: Otomatis dikonversi menjadi 0
- ✅ **Error Handling**: Kesalahan akan dicatat di log Laravel
- ✅ **Batch Processing**: Proses 100 baris sekaligus untuk efisiensi
- ✅ **Satu Total per Kantor**: Setiap kantor hanya memiliki SATU data Total (agregasi dari semua SubSufixes)
- Setiap baris dengan data SubSufix akan membuat record SubSufix baru
- Jika ada multiple baris dengan Kantor/Sufix yang sama, mereka akan menghasilkan multiple SubSufix
- Total dihitung setelah semua data diimport (agregasi level kantor, bukan per sufix)

## ✅ Hasil Test Import test-import.csv:

**Import Statistics:**
- 📊 **33 Kantors** imported
- 📋 **99 Sufixes** created  
- 📝 **132 SubSufixes** imported
- 🧮 **99 Total records** auto-generated

**Sample Results:**
- **Banjarnegara-53400**: 3 Sufixes, 3 SubSufixes total
  - Sufix A: 854 → 849 (99.41%)
  - Sufix B: 5,068 → 5,004 (98.74%)
  - Sufix C: 6,735 → 6,665 (98.96%)

**Perfect Import Success dengan Format Indonesia! ✅**
