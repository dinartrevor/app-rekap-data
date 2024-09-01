<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasePlaintiff extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'legal_case_id',
        'name',
        'place_of_birth',
        'date_of_birth',
    ];
}
