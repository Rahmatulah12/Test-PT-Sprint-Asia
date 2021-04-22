<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model{
    protected $table = 'orders';

    protected $fillable = [
        'no', 'user_id', 'status_order_id'
    ];

    public function statusOrder()
    {
        return $this->belongsTo("App\Models\StatusOrder");
    }

    public function detailOrders(){
        return $this->hasMany("App\Models\OrderDetail");
    }

    public function getAllData($size = 10, $keyword = null)
    {
        $orders = $this->where('no', 'like', "%$keyword%")->orWhere('table_no', 'like', "%$keyword%")
        ->where('status_order_id', 1)->paginate($size);
        return $orders;
    }

    public static function generateOrderNumber()
    {
        $data = DB::select("SELECT max(no) as no FROM test_kasir.orders");
        $urutan;
        if ($data[0]->no == null || $data[0]->no == "") {
            $urutan = 1;
        }
        $no_baru;
        foreach ($data as $row) {
            $no_baru = $row->no;
        }
        $format = "ABC" . date("dmY") . "-";
        $urutan = (int) substr($no_baru, 12, 13);
        $urutan++;
        $no_order_baru = $format . sprintf('%03s', $urutan);
        return $no_order_baru;
    }
}
