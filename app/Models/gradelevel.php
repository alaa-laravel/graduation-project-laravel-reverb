<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gradelevel extends Model
{
    /** @use HasFactory<\Database\Factories\GradelevelFactory> */
    use HasFactory;

    protected $guarded =[];

    public function users ()
    {
        return  $this->hasMany(User::class,'grade_level_id');
    }
    public function subjects ()
    {
        return  $this->hasMany(subject::class,'grade_level_id' );
    }
}
