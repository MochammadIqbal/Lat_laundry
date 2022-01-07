<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_transaksi';
    protected $table = 'transaksi';
    protected $fillable =['id_member','tgl','batas_waktu','tgl_bayar','status','dibayar','id_user','subtotal'];
    public $timestamps = false;
}
