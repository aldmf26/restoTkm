<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TblAbsen extends Component
{
    public
        $bulan,
        $tahun,
        $valBulan,
        $valTahun,
        $totalTgl,
        $perPage = 5;
    
    public $listBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    public function mount()
    {
        $this->valBulan = (int) date('m');
        $this->valTahun = (int) date('Y');

        $this->totalTgl = cal_days_in_month(CAL_GREGORIAN, $this->valBulan, $this->valTahun);
    }

    public function getTotal($id_karyawan, $status)
    {
        $c = DB::table('tb_absen')->where([['id_karyawan', $id_karyawan],['status', $status]])->count();
        return $c;
    }

    public function clickEdit($id_absen, $status)
    {
        if($status == 'OFF') {
            DB::table('tb_absen')->where('id_absen', $id_absen)->delete();
        } else {
            DB::table('tb_absen')->where('id_absen', $id_absen)->update([
                'status' => $status,
            ]);
        }
    }


    public function clickOff($id_karyawan, $tgl)
    {
        $id_lokasi = 1;
        $tgl = $this->valTahun . '-' . $this->valBulan . '-' . $tgl;
        DB::table('tb_absen')->insert([
            'id_karyawan' => $id_karyawan,
            'status' => 'M',
            'tgl' => $tgl,
            'id_lokasi' => $id_lokasi,
        ]);
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }
    
    public function render()
    {
        $data = [
            'karyawan' => DB::table('tb_karyawan')->paginate($this->perPage),
        ];

        return view('livewire.tbl-absen', $data);
    }
}
