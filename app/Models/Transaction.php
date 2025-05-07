<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // protected $fillable = [
    //     'user_id', 'reference', 'amount', 'status', 'gateway_response'
    // ];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
