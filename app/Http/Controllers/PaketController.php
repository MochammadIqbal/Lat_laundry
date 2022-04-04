<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PaketModel;

class PaketController extends Controller
{
    //public $user;
    //public function __construct(){
      //  $this->user = JWTAuth::parseToken()->authenticate();
    //}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
		}

        $paket = new PaketModel();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();

        $data = PaketModel::where('id_paket', '=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Data paket berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $data = PaketModel::get();
        return response()->json($data);
    }

    public function getById($id)
    {
        $data = PaketModel::where('id_paket', $id)->first();
        
        return response()->json($data);
    }

    public function update(Request $request, $id_paket)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $paket = PaketModel::where('id_paket', '=', $id_paket)->first();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;

        $paket->save();

        return response()->json([
            'success' => true,
            'message' => 'Data paket berhasil diubah'
        ]);
    }

    public function delete($id_paket)
    {
        $delete = PaketModel::where('id_paket', '=', $id_paket)->delete();

        if($delete) {
            return response()->json([
                'success' => true,
                'message' => "Data paket berhasil dihapus"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Data paket gagal dihapus"
            ]);
        }
    }
}
