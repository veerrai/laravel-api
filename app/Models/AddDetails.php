<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AddDetails extends Model
{
    use HasFactory;

    protected $table = 'add_details';

   protected $fillable = [
    'name',
    'email',
    'address',
    'phone',
    'photo'
   ];

}
