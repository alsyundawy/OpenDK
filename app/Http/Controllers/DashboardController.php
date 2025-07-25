<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Models\DataDesa;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Program;
use App\Services\BantuanService;
use App\Services\DesaService;
use App\Services\KeluargaService;
use App\Services\PendudukService;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $page_title = 'Dashboard';

        if($this->isDatabaseGabungan())
        {
            $data = [
                'desa' => (new DesaService())->jumlahDesa(),
                'penduduk' => (new PendudukService)->jumlahPenduduk(),
                'keluarga' =>  (new KeluargaService)->jumlahKeluarga([
                    'filter[status]' => 1
                ]),
                'program_bantuan' => (new BantuanService)->jumlahBantuan(),
            ];
        }else{
            $data = [
                'desa' => DataDesa::count(),
                'penduduk' => Penduduk::hidup()->count(),
                'keluarga' => Keluarga::whereHas('kepala_kk', static function ($query) {
                    $query->where('kk_level', '1')->where('status_dasar', '1');
                })->count(),
                'program_bantuan' => Program::count(),
            ];
        }

        // Halaman populer
        $top_pages_visited = \App\Models\Visitor::getTopPagesVisited();

        // User agent
        $userAgents = \App\Models\Visitor::query()->pluck('user_agent');

        // Parsing user_agent
        $browserCounts = [];
        $deviceCounts = [];
        $platformCounts = [];

        foreach ($userAgents as $userAgent) {
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($userAgent);

            $browser = $agent->browser() ?: 'Unknown';
            $device = $agent->device() ?: 'Unknown';
            $platform = $agent->platform() ?: 'Unknown';

            $browserCounts[$browser] = ($browserCounts[$browser] ?? 0) + 1;
            $deviceCounts[$device] = ($deviceCounts[$device] ?? 0) + 1;
            $platformCounts[$platform] = ($platformCounts[$platform] ?? 0) + 1;
        }

        // Konversi ke format yang sesuai untuk Highcharts
        $browserData = $this->convertToHighchartFormat($browserCounts);
        $deviceData = $this->convertToHighchartFormat($deviceCounts);
        $platformData = $this->convertToHighchartFormat($platformCounts);


        return view('dashboard.index', compact('page_title', 'data', 'top_pages_visited', 'browserData', 'deviceData', 'platformData'));
    }

    /**
     * Ubah array associative count ke format Highcharts
     *
     * @param array $counts
     * @return array
     */
    private function convertToHighchartFormat(array $counts): array
    {
        return array_map(function ($count, $name) {
            return ['name' => $name, 'y' => $count];
        }, $counts, array_keys($counts));
    }
}
