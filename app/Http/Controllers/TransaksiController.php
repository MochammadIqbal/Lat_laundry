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
    public $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
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
        // $transaksi->id_member = $this->id_member;
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl_order = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum dibayar';
        $transaksi->subtotal = NULL;
        $transaksi->id = $this->user->id;

        $transaksi->save();

        $data = TransaksiModel::where('id_transaksi', '=', $transaksi->id_transaksi)->first();

        return response()->json(['message' => 'Data transaksi berhasil ditambahkan', 'data' => $data]);
    }
    public function getAll()
    {
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                    ->select('transaksi.*', 'member.nama')
                    ->get();
                    
        return response()->json($data);
    }
    
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = TransaksiModel::find($id);

        $transaksi->update($request->all());

        return response()->json(['message' => 'Transaksi berhasil diubah']);
    }

    public function getById($id)
    {
        $data = TransaksiModel::where('id_transaksi', '=', $id)->first();  
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')      
                                      ->select('transaksi.*', 'member.nama')
                                      ->where('transaksi.id_transaksi', '=', $id)
                                      ->first();
        return response()->json($data);
    }

    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = TransaksiModel::where('id_transaksi', '=', $id)->first();
        $transaksi->status = $request->status;

        $transaksi->save();

        return response()->json(['message' => 'Status berhasil diubah', $transaksi]);
    }
    
    public function bayar($id)
    {
        $transaksi = TransaksiModel::where('id', '=', $id)->first();
        $total = DetilTransaksiModel::where('id_transaksi', $id)->sum('subtotal');

        $transaksi->tgl_bayar = Carbon::now();
        $transaksi->status = "diambil";
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
        
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                    ->select('transaksi.id_transaksi','transaksi.tgl_order','transaksi.tgl_bayar','transaksi.subtotal', 'member.nama')
                    ->whereYear('tgl_order', '=' , $tahun)
                    ->whereMonth('tgl_order', '=', $bulan)
                    ->get();

        return response()->json($data);
    }

} 