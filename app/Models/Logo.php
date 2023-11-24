<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'family_id', 'font_id', 'palette_id', 'icon_id', 'type', 'status'];

    public function topic() {
        return $this->belongsTo(Topic::class);
    }

    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function font() {
        return $this->belongsTo(Font::class);
    }

    public function palette() {
        return $this->belongsTo(Palette::class);
    }

    public function icon() {
        return $this->belongsTo(Icon::class);
    }
}
