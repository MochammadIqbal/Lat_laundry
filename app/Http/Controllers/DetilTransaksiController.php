<?php

namespace App\Http\Controllers;

use App\Models\DetilTransaksiModel;
use App\Models\PaketModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;


class DetilTransaksiController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required',
            'id_paket' => 'required',
            'quantity' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $detail = new DetilTransaksiModel();
        $detail->id_transaksi = $request->id_transaksi;
        $detail->id_paket = $request->id_paket;

        //GET HARGA PAKET
        $paket = PaketModel::where('id_paket', '=', $detail->id_paket)->first();
        $harga = $paket->harga;

        $detail->quantity = $request->quantity;
        $detail->subtotal = $detail->quantity * $harga;

        $detail->save();

        $data = DetilTransaksiModel::where('id_detail_transaksi', '=', $detail->id_detail_transaksi)->first();

        return response()->json(['message' => 'Berhasil tambah detil transaksi', 'data' => $data]);
    }

    public function getById($id)
    {
        //untuk ambil detil dari transaksi tertentu
        $data = DB::table('detail_transaksi')->join('paket', 'detail_transaksi.id_paket', 'paket.id_paket')
                                            ->select('detail_transaksi.*', 'paket.jenis')
                                            ->where('detail_transaksi.id_transaksi', '=', $id)
                                            ->get();
                                            return response()->json($data);
}

    public function getTotal($id_detail)
    {
        $total = DetilTransaksiModel::where('id_detail_transaksi', $id_detail)->sum('subtotal');
        
        return response()->json([
            'total' => $total
        ]);
    }
}