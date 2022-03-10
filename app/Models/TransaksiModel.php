<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    protected $primaryKey = 'id_transaksi';
    protected $table = 'transaksi';

    protected $fillable = ['id_member','tgl','batas_waktu','tgl_bayar','status','dibayar','id_user'];
    public $timestamps = false;

    // public function detail()
    // {
    //     return $this->hasMany(DetilTransaksi::class,'id_transaksi', 'id');
    // }
}
    //protected $fillable =['id_member','tgl','batas_waktu','tgl_bayar','status','dibayar','id_user','subtotal'];
    //public $timestamps = false;

