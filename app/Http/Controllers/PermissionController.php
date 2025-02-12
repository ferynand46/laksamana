<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // function __construct()
    // {
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
    // }
    public function index()
    {
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'koordinator']);
        // Role::create(['name' => 'anggota']);
        // Role::create(['name' => 'kepala']);
        // Role::create(['name' => 'petugas']);
        // exit;

        // $role = Role::findByName('admin'); // Ganti 'role_name' dengan nama role yang ingin dihapus
        // $role->delete();
        // exit;
        #check has roles    
        
        // $admin = User::find(1);
        // // Assign role
        // $roleNames = Role::pluck('name')->toArray();
        // $admin->syncRoles($roleNames);

        // // Cek role yang dimiliki user
        // return response()->json([
        //     'user_roles' => $admin->getRoleNames() // Mengambil daftar role yang dimiliki user
        // ]);
        // exit;


        // Ambil semua role yang ada di database
        #SET ROLES ADMIN
        // $assygn=$admin->syncRoles($roleNames);
        $roles      = Role::all(); 
        $admin      = User::find(20);
        $admin->assignRole("petugas");
        exit;
        $roleNames  = []; // Array untuk menyimpan nama role
        foreach ($roles as $role) {
            $roleNames[] = $role->name; // Simpan nama role
        }
        #print implode(",",$roleNames);
       
        $assygn=$admin->syncRoles($roleNames);
        // if ($admin->hasRole('administrator')) {
        //     return "User ini adalah admin";
        // }else{
        //     return "User ini bukan admin";
        // }
        

        #set kepala
        $kepala      = User::find(2);
        $kepala->assignRole('kepala');


        #SET ROLES ASSESSOR
        $assesor1 = User::find(8); // Temukan user dengan ID 1
        $assesor1->assignRole("assesor");
        $assesor2 = User::find(9); // Temukan user dengan ID 1
        $assesor2->assignRole("assesor");
        $assesor3 = User::find(10); // Temukan user dengan ID 1
        $assesor3->assignRole("assesor");

        $petugas1 = User::find(4); 
        $petugas1->assignRole("petugas");
        $petugas2 = User::find(6); 
        $petugas2->assignRole("petugas");
        $petugas3 = User::find(8); 
        $petugas3->assignRole("petugas");
        $petugas4 = User::find(9); 
        $petugas4->assignRole("petugas");
        $petugas5 = User::find(10); 
        $petugas5->assignRole("petugas");

        for($x=11;$x<20;$x++){
            $pendamping[$x] = User::find($x); 
            $pendamping[$x]->assignRole("pendamping");
        }

        // dd($roles);
        return response()->json([
            'message' => 'Semua role berhasil diberikan ke user!',
            // 'user' => $user->name,
            'roles' => $roleNames
        ]);
        // $user->assignRole(['admin', 'editor']); 
        // return view('permission.index', compact('role'));
    }

    public function store(Request $request)
    {
        Role::create(['name' => $request->name]);
        return redirect()->route('permission');
    }
    public function edit($id)
    {
        $role = Role::find($id);
        return view('permission.edit', compact('role'));
    }
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        return redirect()->route('permission');
    }
    public function create()
    {
        return view('permission.create');
    }
    public function show($id)
    {
        $role = Role::find($id);
        return view('permission.show', compact('role'));
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('permission');
    }
}
