<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'family1_id', 'family2_id', 'company_name', 'desc', 'suggestions'];

    public function logos() {
        $this->hasMany(Logo::class);
    }
}
