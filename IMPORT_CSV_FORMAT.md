# CSV Import Format for Kantor Data

## Overview
This d- **Kantor Creation/Update**: The importer will find or create Kantor records based on the `nopen` field
- **Sufix Creation**: If `nama_sufix` is provided, a Sufix record will be created for that Kantor
- **SubSufix Creation**: If any sub_* columns have data, a SubSufix record will be created linked to the Sufix
- **Total Creation**: If any total_* columns have data, a Total record will be created linked to the Sufix

## Notes

- Empty cells in optional columns will be treated as 0 for numeric fields
- The `nopen` field is used as the unique identifier for Kantor records
- If a Kantor with the same `nopen` already exists, it will be updated with new data
- Related records (Sufix, SubSufix, Total) will be created as new records each time
- All numeric fields should contain valid numbers (integers or decimals)
- String fields should be enclosed in quotes if they contain commas or special charactersibes the CSV format required to import Kantor data with related Sufix, SubSufix, and Total records.

## CSV Columns Structure

### Required Columns (Kantor Data)
1. **kantor** - Name of the Kantor office
2. **nopen** - Nopen identifier (used as unique key)
3. **kab_kota** - Kabupaten/Kota location
4. **alokasi_kpm** - Alokasi KPM (numeric)
5. **alokasi_jml_uang** - Alokasi Jumlah Uang (numeric)

### Optional Columns (Sufix Data)
6. **nama_sufix** - Name of the Sufix

### Optional Columns (SubSufix Data)
7. **sub_alokasi** - Sub Alokasi (numeric)
8. **sub_alokasi_biaya** - Sub Alokasi Biaya (numeric)
9. **sub_realisasi** - Sub Realisasi (numeric)
10. **sub_realisasi_biaya** - Sub Realisasi Biaya (numeric)
11. **sub_gagal_bayar_tolak** - Sub Gagal Bayar Tolak (numeric)
12. **sub_sisa_aktif** - Sub Sisa Aktif (numeric)
13. **sub_sisa_biaya** - Sub Sisa Biaya (numeric)

### Optional Columns (Total Data)
14. **total_jumlah_alokasi_bnba** - Total Jumlah Alokasi BNBA (numeric)
15. **total_jumlah_alokasi_biaya** - Total Jumlah Alokasi Biaya (numeric)
16. **total_jumlah_realisasi** - Total Jumlah Realisasi (numeric)
17. **total_jumlah_realisasi_biaya** - Total Jumlah Realisasi Biaya (numeric)
18. **total_persentase** - Total Persentase (numeric)

## Sample CSV Data

```csv
kantor,nopen,kab_kota,alokasi_kpm,alokasi_jml_uang,nama_sufix,sub_alokasi,sub_alokasi_biaya,sub_realisasi,sub_realisasi_biaya,sub_gagal_bayar_tolak,sub_sisa_aktif,sub_sisa_biaya,total_jumlah_alokasi_bnba,total_jumlah_alokasi_biaya,total_jumlah_realisasi,total_jumlah_realisasi_biaya,total_persentase
"Kantor Pos Jakarta Pusat",12345,"Jakarta Pusat",1000,5000000,"Sufix A",500,2500000,450,2250000,10,40,250000,1000,5000000,900,4500000,90
"Kantor Pos Bandung",23456,"Bandung",1500,7500000,"Sufix B",750,3750000,700,3500000,15,35,250000,1500,7500000,1400,7000000,93.33
"Kantor Pos Surabaya",34567,"Surabaya",2000,10000000,"Sufix C",1000,5000000,950,4750000,20,30,250000,2000,10000000,1900,9500000,95
```

## Multiple Relations Per Kantor

If a Kantor has multiple Sufixes, SubSufixes, or Totals, you can include multiple rows with the same `nopen` but different relational data:

```csv
kantor,nopen,kab_kota,alokasi_kpm,alokasi_jml_uang,nama_sufix,sub_alokasi,sub_alokasi_biaya,sub_realisasi,sub_realisasi_biaya,sub_gagal_bayar_tolak,sub_sisa_aktif,sub_sisa_biaya,total_jumlah_alokasi_bnba,total_jumlah_alokasi_biaya,total_jumlah_realisasi,total_jumlah_realisasi_biaya,total_persentase
"Kantor Pos Jakarta Pusat",12345,"Jakarta Pusat",1000,5000000,"Sufix A",500,2500000,450,2250000,10,40,250000,1000,5000000,900,4500000,90
"Kantor Pos Jakarta Pusat",12345,"Jakarta Pusat",1000,5000000,"Sufix B",300,1500000,280,1400000,5,15,100000,600,3000000,560,2800000,93.33
"Kantor Pos Jakarta Pusat",12345,"Jakarta Pusat",1000,5000000,"Sufix C",200,1000000,190,950000,3,7,50000,400,2000000,380,1900000,95
```

## Import Process

1. **Kantor Creation/Update**: The importer will find or create Kantor records based on the `nopen` field
2. **Sufix Creation**: If `nama_sufix` is provided, a Sufix record will be created for that Kantor
3. **SubSufix Creation**: If any sub_* columns have data, a SubSufix record will be created linked to the Sufix
4. **Total Creation**: If any total_* columns have data, a Total record will be created linked to the Sufix

## Notes

- Empty cells in optional columns will be treated as 0 for numeric fields
- The `nopen` field is used as the unique identifier for Kantor records
- If a Kantor with the same `nopen` already exists, it will be updated with new data
- Related records (Sufix, SubSufix, Total) will be created as new records each time
- All numeric fields should contain valid numbers (integers or decimals)
- String fields should be enclosed in quotes if they contain commas or special characters
