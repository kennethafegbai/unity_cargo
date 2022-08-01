<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ItemPickUp extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'collection_address',
        'postal_code',
        'package_type',  
        'commodity_type',
        'collection_date',
        'collection_time',
        'item_description',
        'estimated_weight',
        'number_of_boxes',
        'receiver_first_name',
        'receiver_last_name',
        'receiver_address',
        'receiver_city',
        'receiver_state',
        'receiver_phone_number',
        'receiver_phone_number2',
        'delivery_type',
        'payment_type',
        'tracking_id',
        'status'
    ];

=======
    protected $guarded = [];

    public function History()
    {
        return $this->hasMany(ParcelHistory::class, "parcel_id", "id");
    }
>>>>>>> a2b680974174f969dabfe616c9894ad3329f2225
}
