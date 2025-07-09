<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class KantorSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Kosongkan tabel sebelum mengisi untuk menghindari duplikasi data
        DB::table('kantor')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['kantor' => 'BANJARNEGARA-53400', 'nopen' => 53400, 'Kab/Kota' => 'BANJARNEGARA', 'alokasi_kpm' => '12.657', 'alokasi_jml_uang' => 9831300000],
            ['kantor' => 'BANTUL-55700', 'nopen' => 55700, 'Kab/Kota' => 'BANTUL', 'alokasi_kpm' => '6.458', 'alokasi_jml_uang' => 5105625000],
            ['kantor' => 'BLORA-58200', 'nopen' => 58200, 'Kab/Kota' => 'BLORA', 'alokasi_kpm' => '9.104', 'alokasi_jml_uang' => 6806650000],
            ['kantor' => 'BOYOLALI-57300', 'nopen' => 57300, 'Kab/Kota' => 'BOYOLALI', 'alokasi_kpm' => '7.357', 'alokasi_jml_uang' => 5234175000],
            ['kantor' => 'BREBES-52200', 'nopen' => 52200, 'Kab/Kota' => 'BREBES', 'alokasi_kpm' => '22.262', 'alokasi_jml_uang' => 16701975000],
            ['kantor' => 'CILACAP-53200', 'nopen' => 53200, 'Kab/Kota' => 'CILACAP', 'alokasi_kpm' => '16.736', 'alokasi_jml_uang' => 11564700000],
            ['kantor' => 'JEPARA-59400', 'nopen' => 59400, 'Kab/Kota' => 'JEPARA', 'alokasi_kpm' => '116.803', 'alokasi_jml_uang' => 99622775000],
            ['kantor' => 'KARANGANYAR-57700', 'nopen' => 57700, 'Kab/Kota' => 'KARANGANYAR', 'alokasi_kpm' => '5.425', 'alokasi_jml_uang' => 3904000000],
            ['kantor' => 'KEBUMEN-54300', 'nopen' => 54300, 'Kab/Kota' => 'KEBUMEN', 'alokasi_kpm' => '19.619', 'alokasi_jml_uang' => 15407775000],
            ['kantor' => 'KENDAL-51300', 'nopen' => 51300, 'Kab/Kota' => 'KENDAL', 'alokasi_kpm' => '81.080', 'alokasi_jml_uang' => 72868675000],
            ['kantor' => 'KLATEN-57400', 'nopen' => 57400, 'Kab/Kota' => 'KLATEN', 'alokasi_kpm' => '6.960', 'alokasi_jml_uang' => 5318250000],
            ['kantor' => 'KUDUS-59300', 'nopen' => 59300, 'Kab/Kota' => 'KUDUS', 'alokasi_kpm' => '8.386', 'alokasi_jml_uang' => 5823550000],
            ['kantor' => 'MAGELANG-56100', 'nopen' => 56100, 'Kab/Kota' => 'KOTA MAGELANG', 'alokasi_kpm' => '941', 'alokasi_jml_uang' => 633600000],
            ['kantor' => null, 'nopen' => 56100, 'Kab/Kota' => 'MAGELANG', 'alokasi_kpm' => '12.273', 'alokasi_jml_uang' => 8906650000],
            ['kantor' => 'PATI-59100', 'nopen' => 59100, 'Kab/Kota' => 'PATI', 'alokasi_kpm' => '13.655', 'alokasi_jml_uang' => 10039225000],
            ['kantor' => null, 'nopen' => 59100, 'Kab/Kota' => 'REMBANG', 'alokasi_kpm' => '5.060', 'alokasi_jml_uang' => 3737725000],
            ['kantor' => 'PEKALONGAN-51100', 'nopen' => 51100, 'Kab/Kota' => 'BATANG', 'alokasi_kpm' => '21.954', 'alokasi_jml_uang' => 15112225000],
            ['kantor' => null, 'nopen' => 51100, 'Kab/Kota' => 'KOTA PEKALONGAN', 'alokasi_kpm' => '3.525', 'alokasi_jml_uang' => 2425000000],
            ['kantor' => null, 'nopen' => 51100, 'Kab/Kota' => 'PEKALONGAN', 'alokasi_kpm' => '10.894', 'alokasi_jml_uang' => 7703075000],
            ['kantor' => 'PEMALANG-52300', 'nopen' => 52300, 'Kab/Kota' => 'PEMALANG', 'alokasi_kpm' => '14.342', 'alokasi_jml_uang' => 11502950000],
            ['kantor' => 'PURBALINGGA-53300', 'nopen' => 53300, 'Kab/Kota' => 'PURBALINGGA', 'alokasi_kpm' => '14.423', 'alokasi_jml_uang' => 10811400000],
            ['kantor' => 'PURWODADIGROBOGAN-58100', 'nopen' => 58100, 'Kab/Kota' => 'GROBOGAN', 'alokasi_kpm' => '9.613', 'alokasi_jml_uang' => 7391375000],
            ['kantor' => 'PURWOKERTO-53100', 'nopen' => 53100, 'Kab/Kota' => 'BANYUMAS', 'alokasi_kpm' => '17.478', 'alokasi_jml_uang' => 12687775000],
            ['kantor' => 'PURWOREJO-54100', 'nopen' => 54100, 'Kab/Kota' => 'PURWOREJO', 'alokasi_kpm' => '3.527', 'alokasi_jml_uang' => 2704200000],
            ['kantor' => 'SALATIGA-50700', 'nopen' => 50700, 'Kab/Kota' => 'KOTA SALATIGA', 'alokasi_kpm' => '2.416', 'alokasi_jml_uang' => 1836500000],
            ['kantor' => null, 'nopen' => 50700, 'Kab/Kota' => 'SEMARANG', 'alokasi_kpm' => '6.401', 'alokasi_jml_uang' => 4350525000],
            ['kantor' => 'SEMARANG-50000', 'nopen' => 50000, 'Kab/Kota' => 'DEMAK', 'alokasi_kpm' => '11.202', 'alokasi_jml_uang' => 7896625000],
            ['kantor' => null, 'nopen' => 50000, 'Kab/Kota' => 'KOTA SEMARANG', 'alokasi_kpm' => '8.077', 'alokasi_jml_uang' => 6226675000],
            ['kantor' => 'SOLO-57100', 'nopen' => 57100, 'Kab/Kota' => 'KARANGANYAR', 'alokasi_kpm' => '1.427', 'alokasi_jml_uang' => 1034875000],
            ['kantor' => null, 'nopen' => 57100, 'Kab/Kota' => 'KOTA SURAKARTA', 'alokasi_kpm' => '5.609', 'alokasi_jml_uang' => 4001000000],
            ['kantor' => null, 'nopen' => 57100, 'Kab/Kota' => 'SUKOHARJO', 'alokasi_kpm' => '1.353', 'alokasi_jml_uang' => 949200000],
            ['kantor' => 'SRAGEN-57200', 'nopen' => 57200, 'Kab/Kota' => 'SRAGEN', 'alokasi_kpm' => '10.620', 'alokasi_jml_uang' => 8042675000],
            ['kantor' => 'SUKOHARJO-57500', 'nopen' => 57500, 'Kab/Kota' => 'SUKOHARJO', 'alokasi_kpm' => '5.295', 'alokasi_jml_uang' => 3852725000],
            ['kantor' => 'TEGAL-52100', 'nopen' => 52100, 'Kab/Kota' => 'KOTA TEGAL', 'alokasi_kpm' => '3.294', 'alokasi_jml_uang' => 2348250000],
            ['kantor' => null, 'nopen' => 52100, 'Kab/Kota' => 'TEGAL', 'alokasi_kpm' => '13.727', 'alokasi_jml_uang' => 9760600000],
            ['kantor' => 'TEMANGGUNG-56200', 'nopen' => 56200, 'Kab/Kota' => 'TEMANGGUNG', 'alokasi_kpm' => '2.659', 'alokasi_jml_uang' => 1910625000],
            ['kantor' => 'UNGARAN-50500', 'nopen' => 50500, 'Kab/Kota' => 'SEMARANG', 'alokasi_kpm' => '4.167', 'alokasi_jml_uang' => 2734150000],
            ['kantor' => 'WATESYOGYA-55600', 'nopen' => 55600, 'Kab/Kota' => 'KULON PROGO', 'alokasi_kpm' => '5.260', 'alokasi_jml_uang' => 3958775000],
            ['kantor' => 'WONOGIRI-57600', 'nopen' => 57600, 'Kab/Kota' => 'WONOGIRI', 'alokasi_kpm' => '13.194', 'alokasi_jml_uang' => 9204325000],
            ['kantor' => 'WONOSARIYOGYA-55800', 'nopen' => 55800, 'Kab/Kota' => 'GUNUNGKIDUL', 'alokasi_kpm' => '3.149', 'alokasi_jml_uang' => 2458450000],
            ['kantor' => 'WONOSOBO-56300', 'nopen' => 56300, 'Kab/Kota' => 'WONOSOBO', 'alokasi_kpm' => '10.694', 'alokasi_jml_uang' => 8382175000],
            ['kantor' => 'YOGYAKARTA-55000', 'nopen' => 55000, 'Kab/Kota' => 'BANTUL', 'alokasi_kpm' => '3.822', 'alokasi_jml_uang' => 2976450000],
        ];

        // Tambahkan timestamp untuk created_at dan updated_at
        $now = Carbon::now();
        $data = array_map(function($item) use ($now) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
            return $item;
        }, $data);

        // Masukkan data ke dalam database
        DB::table('kantor')->insert($data);
    }

}
