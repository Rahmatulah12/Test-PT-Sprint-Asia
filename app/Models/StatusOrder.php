<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusOrder extends Model
{
    protected $table = 'status_orders';

    protected $fillable = [
        'name',
    ];

    public function orders(){
        return $this->hasMany("App\Models\Order");
    }
}
