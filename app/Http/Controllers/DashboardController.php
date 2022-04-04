<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberModel;
use App\Models\TransaksiModel;

class DashboardController extends Controller
{
    public function index()
    {
        $member = MemberModel::count();
        $baru = TransaksiModel::where('status', '=', 'Baru')->count();
        $proses = TransaksiModel::where('status', '=', 'Proses')->count();
        $pendapatan = TransaksiModel::where('dibayar', '=', 'dibayar')->sum('total');

        return response()->json([
            'member' => $member,
            'baru' => $baru,
            'proses' => $proses,
            'pendapatan' => $pendapatan,
        ]);
    }
}