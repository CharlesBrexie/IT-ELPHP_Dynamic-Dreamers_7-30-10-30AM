<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Donation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'itemName',
        'address',
        'timeOfPreparation',
        'quantity',
        'utensilsNeeded',
        'charity',
        'imageUrl',
        'user_id',
        'DateDonated'
    ];


    public function requests()
    {
        return $this->hasMany(Request::class, 'donationId');
    }
}
