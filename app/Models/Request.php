<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'donationId',
        'ngoId',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donationId', 'donationId');
    }
}
