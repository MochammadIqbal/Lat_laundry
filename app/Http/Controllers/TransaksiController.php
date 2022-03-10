<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\TransaksiModel;
use App\Models\DetilTransaksiModel;
use Carbon\Carbon;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;




class TransaksiController extends Controller
{
    public $users;

    public function __construct()
    {
        $this->users = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = new TransaksiModel();
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        // $transaksi->tgl_bayar = Carbon::now();
        $transaksi->status = 'baru';
        $transaksi->dibayar = $request->dibayar;
        //$transaksi->id = $this->user->id;
        $transaksi->id_user = $this->users->id_user;

        $transaksi->save();

        $data = TransaksiModel::where('id_transaksi', '=', $transaksi->id_transaksi)->first();

        return response()->json(['message' => 'Data transaksi berhasil ditambahkan', 'data' => $data]);
    }
    public function getAll()
    {
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                    ->select('transaksi.*', 'member.nama_member')
                    ->get();
                    
        return response()->json(['success' => true, 'data' => $data]);
    }
    
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = TransaksiModel::where('id_transaksi', '=', $id)->first();
        $transaksi->id_member = $request->id_member;

        $transaksi->save();

        return response()->json(['message' => 'Transaksi berhasil diubah']);
    }

    public function getById($id)
    {
        $data = TransaksiModel::where('id_transaksi', '=', $id)->first();  
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')      
                                      ->select('transaksi.*', 'member.nama_member')
                                      ->where('transaksi.id_transaksi', '=', $id)
                                      ->first();
        return response()->json($data);
    }

    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $transaksi = TransaksiModel::where('id', '=', $id)->first();
        $transaksi->status = $request->status;
        
        $transaksi->save();
        
        return response()->json(['message' => 'Status berhasil diubah']);
    }
    
    public function bayar($id)
    {
        $transaksi = TransaksiModel::where('id', '=', $id)->first();
        $total = DetilTransaksiModel::where('id_transaksi', $id)->sum('subtotal');

        $transaksi->tgl_bayar = Carbon::now();
        $transaksi->status = "Diambil";
        $transaksi->dibayar = "dibayar";
        $transaksi->total_bayar = $total;        
        
        $transaksi->save();
        
        return response()->json(['message' => 'Pembayaran berhasil']);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id')
                    ->select('transaksi.id','transaksi.tgl_order','transaksi.tgl_bayar','transaksi.total_bayar', 'member.nama')
                    ->whereYear('tgl_order', '=' , $tahun)
                    ->whereMonth('tgl_order', '=', $bulan)
                    ->get();

        return response()->json($data);
    }

} 