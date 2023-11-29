<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    use HasFactory;

    protected $fillable = ['family_id', 'font_id', 'palette_id', 'icon_id', 'type', 'score'];
}
