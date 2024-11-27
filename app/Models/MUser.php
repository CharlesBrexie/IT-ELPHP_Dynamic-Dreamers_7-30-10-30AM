<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MUser extends Model
{
    use HasFactory;

    protected $table = 'M_User';  // Table name in the database
    protected $primaryKey = 'UserId';  // Primary key of the table
    public $timestamps = true;  // Enable automatic timestamps

    protected $fillable = [
        'FirstName', 
        'LastName', 
        'UserImage'
    ];
}

