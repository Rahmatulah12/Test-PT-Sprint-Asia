<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id', 'menu_id',
    ];

    public function order()
    {
        return $this->belongsTo("App\Models\Order");
    }

    public function menu()
    {
        return $this->belongsTo("App\Models\Menu");
    }

    public static function saveBatch($data, $id)
    {
        $temp = [];
        $returnBoolean = [];
        foreach($data as $row)
        {
            $temp['order_id'] = $id;
            $temp['menu_id'] = $row['menu_id'];
            $temp['qty'] = $row['qty'];
            $temp['ready'] = [
                "order_id" => $temp["order_id"],
                "menu_id" => $temp['menu_id'],
                "qty" => $temp['qty']
            ];
            $save = DB::table('order_details')->insert($temp['ready']);
            array_push($returnBoolean, $save);
        }
        if(count($returnBoolean) <= 0){
            return false;
        }
        return true;
    }
}
