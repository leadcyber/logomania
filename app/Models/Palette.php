<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palette extends Model
{
    use HasFactory;

    protected $fillable = ['background', 'text1', 'text2', 'icon'];

    public $timestamps = false;
}
