<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    //use HasFactory;
    protected $table = 'member';

    protected $fillable = ['nama_member','alamat','jenis_kelamin','tlp'];
}
