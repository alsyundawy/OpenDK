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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporImunisasi;
use App\Models\Imunisasi;
use App\Services\DesaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class ImunisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Imunisasi';
        $page_description = 'Daftar Imunisasi';

        return view('data.imunisasi.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        $listDesa = (new DesaService)->listDesa()->pluck('nama', 'desa_id');
        return DataTables::of(Imunisasi::with(['desa'])->get())
            ->addColumn('aksi', function ($row) {
                $data['edit_url'] = route('data.imunisasi.edit', $row->id);
                $data['delete_url'] = route('data.imunisasi.destroy', $row->id);

                return view('forms.aksi', $data);
            })->addColumn('nama_desa', function ($row) use ($listDesa){
                return $row->desa->nama ?? $listDesa[$row->desa_id] ?? '-';
            })
            ->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
            })
            ->rawColumns(['aksi'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title = 'Imunisasi';
        $page_description = 'Impor Imunisasi';
        $years_list = years_list();
        $months_list = months_list();

        return view('data.imunisasi.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        try {
            (new ImporImunisasi($request->only(['bulan', 'tahun'])))
                ->queue($request->file('file'));
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Import data gagal. '.$e->getMessage());
        }

        return redirect()->route('data.imunisasi.index')->with('success', 'Import data sukses.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $imunisasi = Imunisasi::findOrFail($id);
        $page_title = 'Imunisasi';
        $page_description = 'Ubah Imunisasi : Cakupan Imunisasi'.$imunisasi->cakupan_imunisasi;

        return view('data.imunisasi.edit', compact('page_title', 'page_description', 'imunisasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'cakupan_imunisasi' => 'required',
        ]);

        try {
            Imunisasi::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Data gagal diubah! '.$e->getMessage());
        }

        return redirect()->route('data.imunisasi.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Imunisasi::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.imunisasi.index')->with('error', 'Data gagal dihapus!');
        }

        return redirect()->route('data.imunisasi.index')->with('success', 'Data sukses dihapus!');
    }
}
