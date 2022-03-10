<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilTransaksiModel extends Model
{
    protected $primaryKey = 'id_detail';
    protected $table = 'detil_transaksi';
    protected $fillable = ['id_transaksi', 'id_paket', 'quantity', 'subtotal'];
    public $timestamps =  false;
}