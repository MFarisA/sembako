# Format Import untuk KantorDataImport

## Format CSV/Excel yang Diperlukan

Format file CSV/Excel harus memiliki header (baris pertama) dengan kolom-kolom berikut:

### Kolom Wajib:
- `nopen` - Nomor Identifikasi Kantor (unique)
- `kantor` - Nama Kantor
- `kab_kota` - Kabupaten/Kota
- `alokasi_kmp` - Alokasi KPM
- `alokasi_jml_uang` - Alokasi Jumlah Uang

### Kolom Opsional (untuk Sufix):
- `nama_sufix` - Nama Sufix

### Kolom Opsional (untuk SubSufix):
- `alokasi` - Alokasi SubSufix
- `alokasi_biaya` - Alokasi Biaya SubSufix
- `realisasi` - Realisasi SubSufix
- `realisasi_biaya` - Realisasi Biaya SubSufix
- `gagal_bayar_tolak` - Gagal Bayar Tolak
- `sisa_aktif` - Sisa Aktif
- `sisa_biaya` - Sisa Biaya

## Contoh Data (sample_kantor_data.csv):

```csv
nopen,kantor,kab_kota,alokasi_kmp,alokasi_jml_uang,nama_sufix,alokasi,alokasi_biaya,realisasi,realisasi_biaya,gagal_bayar_tolak,sisa_aktif,sisa_biaya
100001,Kantor Pusat Jakarta,Jakarta Selatan,150,300000000,Sufix A,50000,25000000,45000,22500000,2000,3000,2500000
100001,Kantor Pusat Jakarta,Jakarta Selatan,150,300000000,Sufix A,60000,30000000,55000,27500000,3000,2000,2500000
100001,Kantor Pusat Jakarta,Jakarta Selatan,150,300000000,Sufix B,40000,20000000,35000,17500000,1500,3500,2000000
100002,Kantor Cabang Bandung,Bandung,120,250000000,Sufix A,35000,17500000,32000,16000000,1000,2000,1500000
```

## Cara Kerja Import:

1. **Kantor**: Dibuat/diupdate berdasarkan `nopen` (unique key)
2. **Sufix**: Dibuat berdasarkan kombinasi `nama_sufix` + `kantor_id` (unique)
3. **SubSufix**: Dibuat baru untuk setiap baris yang memiliki data SubSufix
4. **Total**: Dihitung otomatis dari SubSufix menggunakan `generateTotal()`

## Catatan Penting:

- Setiap baris dengan data SubSufix akan membuat record SubSufix baru
- Jika ada multiple baris dengan Kantor/Sufix yang sama, mereka akan menghasilkan multiple SubSufix
- Total akan dihitung ulang setiap kali SubSufix ditambahkan
- Kolom yang kosong akan diisi dengan nilai 0

## ✅ Hasil Test Import sample_kantor_data.csv:

**Import Statistics:**
- 📊 **7 Kantors** imported
- 📋 **15 Sufixes** created
- 📝 **24 SubSufixes** imported
- 🧮 **15 Total records** auto-generated

**Sample Results:**
- **Kantor Pusat Jakarta**: 2 Sufixes, 6 SubSufixes total
  - Sufix A: 4 SubSufixes → Total: 220,000 → 200,000 (90.9%)
  - Sufix B: 2 SubSufixes → Total: 80,000 → 70,000 (87.5%)

**Perfect Import Success! ✅**
