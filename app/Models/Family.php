<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = ['text_code'];

    public $timestamps = false;

    public function fonts() {
        return $this->hasMany(Font::class);
    }

    public function icons() {
        return $this->hasMany(Icon::class);
    }

    public function palettes() {
        return $this->belongsToMany(Palette::class);
    }

    public function combinations() {
        return $this->hasMany(Combination::class);
    }
}
