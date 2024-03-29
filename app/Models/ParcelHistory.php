<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'parcel_id',
        'tracking_id',
        'report',
    ];
}
