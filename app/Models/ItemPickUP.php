<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ItemPickUp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function History()
    {
        return $this->hasMany(ParcelHistory::class, "parcel_id", "id");
    }
}
