<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    use HasFactory;

    protected $fillable = ['family_id', 'filename', 'fontname'];

    public $timestamps = false;

    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }
}
