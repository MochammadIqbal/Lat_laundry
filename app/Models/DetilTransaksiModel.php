<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilTransaksiModel extends Model
{
    protected $primaryKey = 'id_detail_transaksi';
    protected $table = 'detail_transaksi';
    protected $fillable = ['id_transaksi', 'id_paket', 'quantity', 'subtotal'];
}