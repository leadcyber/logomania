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
        $this->hasMany(Font::class);
    }

    public function icons() {
        $this->hasMany(Icon::class);
    }

    public function palettes() {
        $this->belongsToMany(Palette::class);
    }

    public function combinations() {
        $this->hasMany(Combination::class);
    }
}
