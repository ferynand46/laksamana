<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\Agama;
use App\Models\Gedung;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Petugas;
use App\Models\Pengampu;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Pendidikan;
use App\Models\Residensial;
use Illuminate\Support\Str;
use App\Models\KategoriPPKS;
use App\Models\Rehabilitasi;
use App\Models\StatusUsulan;
use Illuminate\Http\Request;
use App\Models\SumberRujukan;
use App\Models\KategoriPPKSSub;

class PersetujuanKepalaController extends Controller
{
    private $error;
    private $success;
    private $status_usulan;
    function __construct()
    {
    //     $fix_roles      =array();
    //     $this->page_attribut  =getPageAttribute();
        
    //     $the_roles      =isset($this->page_attribut[0]['permission']) ? $this->page_attribut[0]['permission']  : array();
    //     $the_url        =isset($this->page_attribut[0]['menuURL']) ? $this->page_attribut[0]['menuURL']  : "";
    //     foreach($the_roles as $vall){
    //         $fix_roles[]=$vall->name;
    //     }
    //     $this->middleware(['permission:'.implode("|",$fix_roles)], ['only' => ['index', 'store']]);
    //     $this->middleware(['permission:create '.$the_url], ['only' => ['create', 'store']]);
    //     $this->middleware(['permission:update '.$the_url], ['only' => ['edit', 'update']]);
    //     $this->middleware(['permission:delete '.$the_url], ['only' => ['destroy','hapusMenu']]);
    //     // dd($fix_roles);
    //     /* OLD
    //     $this->middleware(['permission:read menu|create menu|update menu|delete menu'], ['only' => ['index', 'store']]);
    //     $this->middleware(['permission:create menu'], ['only' => ['create', 'store']]);
    //     $this->middleware(['permission:update menu'], ['only' => ['edit', 'update']]);
    //     $this->middleware(['permission:delete menu'], ['only' => ['destroy']]);
    //     */

    //     $this->error    =array();
    //     $this->success  =false;
        $this->status_usulan=["7078dbc5-db8a-11ef-9f06-244bfebc0c45","be583c52-e600-11ef-bfa8-244bfebc0c45"];
    }
    public function index()
    {
        $residensial = [];
        return view('persetujuan-kepala.index', compact('residensial'));
    }

    public function edit($id){
        // dd($id);
        // $residensial    = Residensial::findOrFail($id);
        $residensial = Residensial::select("laksa_tr_layanan.id as residensial_id","laksa_tr_layanan.*","laksa_ms_ppks.*","laksa_ms_sumber_rujukan.*","laksa_ms_petugas_layanan.*","laksa_ms_status.*","laksa_ms_gedung.*","laksa_ms_pengampu.*");
        $residensial = $residensial->orderby("laksa_tr_layanan.created_at","DESC");
        $residensial = $residensial->leftJoin('laksa_ms_petugas_layanan', 'laksa_tr_layanan.petugas_id', '=', 'laksa_ms_petugas_layanan.id');
        // $residensial = $residensial->leftJoin('laksa_ms_pegawai', 'laksa_ms_pendamping_sosial.pegawai_id', '=', 'laksa_ms_pegawai.id');
        $residensial = $residensial->leftJoin('laksa_ms_ppks', 'laksa_tr_layanan.pasien_id', '=', 'laksa_ms_ppks.id');
        $residensial = $residensial->leftJoin('laksa_ms_sumber_rujukan', 'laksa_tr_layanan.sumber_id', '=', 'laksa_ms_sumber_rujukan.id');
        $residensial = $residensial->leftJoin('laksa_ms_status', 'laksa_tr_layanan.status_id', '=', 'laksa_ms_status.id');
        $residensial = $residensial->leftJoin('laksa_ms_gedung', 'laksa_tr_layanan.gedung_id', '=', 'laksa_ms_gedung.id');
        $residensial = $residensial->leftJoin('laksa_ms_pengampu', 'laksa_tr_layanan.pengampu_id', '=', 'laksa_ms_pengampu.id');
        $residensial = $residensial->where("laksa_tr_layanan.id","=",$id);
        $residensial = $residensial->first();
        // dd($residensial);

        $detail_ppks_value=[];
        // dd(json_decode($residensial->kategori_ppks_json));
        foreach(json_decode($residensial->kategori_ppks_json) as $key=>$detail_ppks){
            if(is_array($detail_ppks)){
                dd("objeck");
            }else{
                if(is_object($detail_ppks)){
                    foreach($detail_ppks as $keyx=>$detail_ppksx){
                        $detail_ppks_value[]=[
                            "combo_box" =>(KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$keyx)->first())->sub_kategori_ppks,
                            "key"       =>$keyx,
                            "value"     =>is_null(KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$detail_ppksx)->first()) ? $detail_ppksx : (KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$detail_ppksx)->first())->sub_kategori_ppks,
                        ];
                    }
                }else{
                    $detail_ppks_value[]=[
                        "combo_box" =>(KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$key)->first())->sub_kategori_ppks,
                        "key"       =>$key,
                        "value"     =>is_null(KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$detail_ppks)->first()) ? $detail_ppks : (KategoriPPKSSub::select("sub_kategori_ppks")->where("id","=",$detail_ppks)->first())->sub_kategori_ppks,
                    ];
                }
            }
        }
        $residensial->kondisi_ppks  =$detail_ppks_value;
        

        $pasien = Pasien::where("laksa_ms_ppks.id", "!=", null);
        $pasien = $pasien->leftJoin('laksa_ms_kabupaten_kota', 'laksa_ms_ppks.kota_id', '=', 'laksa_ms_kabupaten_kota.id')
                        ->leftJoin('laksa_ms_kecamatan', 'laksa_ms_ppks.kecamatan_id', '=', 'laksa_ms_kecamatan.id')
                        ->leftJoin('laksa_ms_provinsi', 'laksa_ms_ppks.provinsi_id', '=', 'laksa_ms_provinsi.id')
                        ->leftJoin('laksa_ms_pendidikan', 'laksa_ms_ppks.pendidikan_id', '=', 'laksa_ms_pendidikan.id')
                        ->leftJoin('laksa_ms_agama', 'laksa_ms_ppks.agama_id', '=', 'laksa_ms_agama.id');
        $pasien = $pasien->where("laksa_ms_ppks.id", $residensial->pasien_id)->first();
        
        $agama          = Agama::all();
        $provinsi       = Provinsi::all();
        $kabupaten      = Kabupaten::all();
        $kecamatan      = Kecamatan::all();
        $pendidikan     = Pendidikan::all();

        return view('persetujuan-kepala.detail', compact('agama','provinsi','kabupaten','kecamatan','pendidikan','residensial','pasien'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Cari data residensial berdasarkan ID
            $residendsial = Residensial::find($request->residensial_id);

            // Jika data tidak ditemukan, kembalikan respons 404
            if (!$residendsial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data residensial tidak ditemukan.',
                ], 404);
            }

            // Update status residensial
            $residendsial->status_id = "7918fc82-db8a-11ef-9f06-244bfebc0c45"; // Kepala Menyetujui Usulan Residensial
            $residendsial->save();

            return response()->json([
                'success' => true,
                'message' => 'Status residensial berhasil diperbarui.',
                'data' => $residendsial,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function load_persetujuan_kepala(){
        $residensial = Residensial::select("laksa_tr_layanan.id as residensial_id","laksa_tr_layanan.*","laksa_ms_ppks.*","laksa_ms_sumber_rujukan.*","laksa_ms_pendamping_sosial.*","laksa_ms_pegawai.*","laksa_ms_status.*");
        $residensial = $residensial->orderby("laksa_tr_layanan.created_at","DESC");
        $residensial = $residensial->leftJoin('laksa_ms_pendamping_sosial', 'laksa_tr_layanan.petugas_id', '=', 'laksa_ms_pendamping_sosial.id');
        $residensial = $residensial->leftJoin('laksa_ms_pegawai', 'laksa_ms_pendamping_sosial.pegawai_id', '=', 'laksa_ms_pegawai.id');
        $residensial = $residensial->leftJoin('laksa_ms_ppks', 'laksa_tr_layanan.pasien_id', '=', 'laksa_ms_ppks.id');
        $residensial = $residensial->leftJoin('laksa_ms_sumber_rujukan', 'laksa_tr_layanan.sumber_id', '=', 'laksa_ms_sumber_rujukan.id');
        $residensial = $residensial->leftJoin('laksa_ms_status', 'laksa_tr_layanan.status_id', '=', 'laksa_ms_status.id');
        $residensial = $residensial->whereIn("status_id",$this->status_usulan);
        $residensial = $residensial->get();
        $data = array();
        $no=0;
        foreach ($residensial as $val) {
            if($val->status_id=="7078dbc5-db8a-11ef-9f06-244bfebc0c45"){
                $tombol_setuju_kepala='<a href="'.route("persetujuankepala.detail",$val->residensial_id).'">
                                                <button class="btn btn-sm btn-icon btn-info">
                                                    <span class="btn-inner">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.4274 2.5783C20.9274 2.0673 20.1874 1.8783 19.4974 2.0783L3.40742 6.7273C2.67942 6.9293 2.16342 7.5063 2.02442 8.2383C1.88242 8.9843 2.37842 9.9323 3.02642 10.3283L8.05742 13.4003C8.57342 13.7163 9.23942 13.6373 9.66642 13.2093L15.4274 7.4483C15.7174 7.1473 16.1974 7.1473 16.4874 7.4483C16.7774 7.7373 16.7774 8.2083 16.4874 8.5083L10.7164 14.2693C10.2884 14.6973 10.2084 15.3613 10.5234 15.8783L13.5974 20.9283C13.9574 21.5273 14.5774 21.8683 15.2574 21.8683C15.3374 21.8683 15.4274 21.8683 15.5074 21.8573C16.2874 21.7583 16.9074 21.2273 17.1374 20.4773L21.9074 4.5083C22.1174 3.8283 21.9274 3.0883 21.4274 2.5783Z" fill="currentColor"></path>
                                                            <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M3.01049 16.8079C2.81849 16.8079 2.62649 16.7349 2.48049 16.5879C2.18749 16.2949 2.18749 15.8209 2.48049 15.5279L3.84549 14.1619C4.13849 13.8699 4.61349 13.8699 4.90649 14.1619C5.19849 14.4549 5.19849 14.9299 4.90649 15.2229L3.54049 16.5879C3.39449 16.7349 3.20249 16.8079 3.01049 16.8079ZM6.77169 18.0003C6.57969 18.0003 6.38769 17.9273 6.24169 17.7803C5.94869 17.4873 5.94869 17.0133 6.24169 16.7203L7.60669 15.3543C7.89969 15.0623 8.37469 15.0623 8.66769 15.3543C8.95969 15.6473 8.95969 16.1223 8.66769 16.4153L7.30169 17.7803C7.15569 17.9273 6.96369 18.0003 6.77169 18.0003ZM7.02539 21.5683C7.17139 21.7153 7.36339 21.7883 7.55539 21.7883C7.74739 21.7883 7.93939 21.7153 8.08539 21.5683L9.45139 20.2033C9.74339 19.9103 9.74339 19.4353 9.45139 19.1423C9.15839 18.8503 8.68339 18.8503 8.39039 19.1423L7.02539 20.5083C6.73239 20.8013 6.73239 21.2753 7.02539 21.5683Z" fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                </button>
                                                </a>';
            }else{
                $tombol_setuju_kepala='';
            }

            if($val->status_id=="be583c52-e600-11ef-bfa8-244bfebc0c45"){
                $rehabilitasi=Rehabilitasi::where("residensial_id","=",$val->residensial_id)->first();
                $tombol_setuju_reviu='<button class="btn btn-sm btn-icon btn-info" Onclick="kepala_reviu_perkembangan(\''.$rehabilitasi->id.'\')">
                                            <span class="btn-inner">
                                                <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                                <g id="SVGRepo_iconCarrier"> <path d="M1468.214 0v551.145L840.27 1179.089c-31.623 31.623-49.693 74.54-49.693 119.715v395.289h395.288c45.176 0 88.093-18.07 119.716-49.694l162.633-162.633v438.206H0V0h1468.214Zm129.428 581.3c22.137-22.136 57.825-22.136 79.962 0l225.879 225.879c22.023 22.023 22.023 57.712 0 79.848l-677.638 677.637c-10.616 10.503-24.96 16.49-39.98 16.49H903.516v-282.35c0-15.02 5.986-29.364 16.49-39.867Zm-920.005 548.095H338.82v112.94h338.818v-112.94Zm225.88-225.879H338.818v112.94h564.697v-112.94Zm734.106-202.5-89.561 89.56 146.03 146.031 89.562-89.56-146.031-146.031Zm-508.228-362.197H338.82v338.818h790.576V338.82Z" fill-rule="evenodd"/> </g>
                                                </svg>
                                            </span>
                                        </button>
                                        ';
            }else{
                $tombol_setuju_reviu='';
            }
            $data[$no]['No']                =($no+1);
            $data[$no]['Nama PPKS']       =$val->nama_depan.' '.$val->nama_belakang.'<br><span class="badge rounded-pill bg-warning">'.$val->status.'</span>';
            $data[$no]['Tgl Penerimaan']    =date("d-m-Y",strtotime($val->tgl_penerimaan));
            $data[$no]['Sumber']            =$val->sumber;
            $data[$no]['Petugas']           =$val->nama;
            $data[$no]['Aksi']              ='<div class="btn-group" role="group" aria-label="Group Aksi">
                                                '.$tombol_setuju_kepala.'
                                                '.$tombol_setuju_reviu.'
                                            </div>';
            $no++;
        }
        return \response()->json($data);
    }

    public function reviuDokumenPerkembangan($id){
        $rehabilitasi = Rehabilitasi::findOrFail($id);
        // dd($rehabilitasi);
        // dd($dokumen_ba);
        $return['dokumen_rehabilitasi']=asset('storage/' . $rehabilitasi->laporan_rehabilitasi);
        $return['residensial_id']= $rehabilitasi->residensial_id;
        
        return $return;
    }
    public function reviuKirimPerkembangan(Request $request){
        function reviu_rules()
            {
            $rules = [
                'residensial_id'          => 'required',
            ];

            $messages = [
                'residensial_id.required'         => 'Kolom ID wajib diisi.',
            ];

            return array("RULE" => $rules, "MESSAGE" => $messages);
        }  

        $validator = \Validator::make($request->all(), reviu_rules()['RULE'],reviu_rules()['MESSAGE']);
        if ($validator->fails()){
            $this->error[]=($validator->errors()->all())[0];
        }else{
            $residensial = new Residensial();
            if ($residensial->where('id', $request->residensial_id)->exists()) {
                
            }else{
                $this->error[]="Data Assesment tidak ada";
            }
        }
        if(!($this->error)){
            $updated = Residensial::where('id', $request->residensial_id)->update(["status_id"=>"f164abdc-e600-11ef-bfa8-244bfebc0c45"]);
            $this->success=true;
        }
        if($this->success){
            $response=[
                'status'=>'success',
                'message'=>"Menyetujui Reviu Kepala Berhasil",
                'redirect'=>route('perujuk')
            ];
        }
        if($this->error){
            $response=[
                'errors'=>'Error',
                'message'=>$this->error,
            ];
        }
        return response()->json($response);
        exit;
    }
}
