<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

   public function getPhotourlAttribute(){
    if($this->photo){
        return assest('photos/' . $this->photo);

    }

    return null;
   }
}
