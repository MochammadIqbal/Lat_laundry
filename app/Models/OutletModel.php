<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletModel extends Model
{
    protected $primaryKey = 'id_outlet';
    protected $table = 'outlet';

    protected $fillable = ['nama', 'alamat'];
}