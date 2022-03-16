<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    //use HasFactory;
    protected $table = 'member';

    protected $primaryKey = 'id_member';

    protected $fillable = ['nama','alamat','jenis_kelamin','tlp'];

}
