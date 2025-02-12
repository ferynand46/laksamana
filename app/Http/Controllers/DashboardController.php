<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\Agama;
use App\Models\Assessment;
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
use App\Models\StatusUsulan;
use App\Models\Rehabilitasi;
use Illuminate\Http\Request;
use App\Models\SumberRujukan;
use App\Models\KategoriPPKSSub;
use App\Models\LaporanTerminasi;

class DashboardController extends Controller
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
        $this->status_usulan=["23ac51ea-db8b-11ef-9f06-244bfebc0c45",'2ae4ad34-db8b-11ef-9f06-244bfebc0c45'];
    }
    public function index()
    {
        $nilai_total = [
            "residensial"       =>Residensial::where("jenis_layanan","Residensial")->count(),#residensial
            "nonresidensial"    =>Residensial::where("jenis_layanan","Non Residensial'")->count(),#nonresidensial
            "ppks"              =>Pasien::count(),#ppks
            "rehabilitasi"      =>Rehabilitasi::count(),#rehabilitasi
            "gedungasrama"      =>Residensial::where("gedung_id","!=",null)->count(),#Gedung::count(),#gedungasrama
            "intervensi"        =>Residensial::where("status_id","be583c52-e600-11ef-bfa8-244bfebc0c45")->count(),#persetujuan_kepala#intervensi#
            "persetujuan_kepala"=>Residensial::where("status_id","7078dbc5-db8a-11ef-9f06-244bfebc0c45")->count(),#persetujuan_kepala
            "assessment"        =>Residensial::where("status_id","23ac51ea-db8b-11ef-9f06-244bfebc0c45")->count(),#assessment
            "terminasi"         =>LaporanTerminasi::count(),#assessment
            
        ];
        return view('dashboard.index', compact('nilai_total'));
    }
}
