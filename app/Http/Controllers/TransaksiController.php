<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TransaksiModels;
use Carbon\carbon;
use JWTAuth;



class TransaksiController extends Controller
{
    //public $response;
    public $user;
    public function __conscruct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'id_member'=> 'required',
        ]);

        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }
        $transaksi =new TransaksiModels();
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum dibayar';
        $transaksi->id_user = $this->user->id_user;
        $member->save();

        $data = TransaksiModel::where('id_transaksi','=', $transaksi->$id_transaksi)->first();

    return response()->json([
        'message' => 'Data member berhasil diinput',
        'data' => $data   
    ]);
        }
}
