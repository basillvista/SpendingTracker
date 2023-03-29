<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable=['status','income','job'];

    protected $table = 'transactions';

    public function user(){
        $this->belongsTo(User::class);
    }

}
