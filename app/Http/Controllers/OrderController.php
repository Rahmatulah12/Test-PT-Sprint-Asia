<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivity as log;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends BaseController
{
    private $menu, $order, $detail;

    public function __construct(){
        $this->menu = new Menu();
        $this->order = new Order();
        $this->detail = new OrderDetail;
    }

    public function index(Request $request)
    {
        $orders = null;
        if(count($request->input()) <= 0){
            $orders = $this->order->getAllData();
        } else {
            $orders = $this->order->getAllData((int)$request->input('size'), $request->input('keyword'));
        }
        log::addToLog(auth()->user()->username . " access order index");
        return response()->json([
            "error" => false,
            "data" => $orders,
        ], 200);
    }

    public function show($id)
    {
        $order = $this->order->find($id);
        if(!$order){
            return response()->json([
                "error" => false,
                "message" => "Data not found.",
            ], 200);
        }
        log::addToLog(auth()->user()->username . " access order detail");
        return response()->json([
            "error" => false,
            "data" => $order,
        ], 200);
    }

    public function delete($id)
    {
        $order = $this->order->find($id);
        if(!$order){
            return response()->json([
                "error" => true,
                "message" => "Data not found.",
            ], 400);
        }

        if($order->status_order_id != 2){
            return response()->json([
                "error" => true,
                "message" => "Order was not active.",
            ], 500);
        }

        $order->status_order_id = 2;
        $delete = $order->save();
        if(!$delete){
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
            ], 500);
        }
        log::addToLog(auth()->user()->username . " deactive order");
        return response()->json([
            "error" => false,
            "message" => "Order was not active.",
        ], 200);
    }

    public function store(Request $request)
    {

        $data = [
            "table_no" => $request->table_no,
            "status_order_id" => $request->status_order_id,
            "details" => $request->details,
        ];

        $validator = Validator::make($data, [
            'table_no' => 'required',
            'status_order_id' => 'required',
            'details' => 'required'
        ]);

        // check validation
        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 400);
        }

        $data['no'] = $this->order->generateOrderNumber();
        $data['user_id'] = auth()->user()->id;
        $save = false;
        $save = DB::transaction(function () use($data){
            $this->order->no = $data['no'];
            $this->order->table_no = $data['table_no'];
            $this->order->user_id = $data['user_id'];
            $this->order->status_order_id = $data['status_order_id'];
            $this->order->save();

            $saveOrderDetail = $this->detail->saveBatch($data['details'], $this->order->id);
            log::addToLog(auth()->user()->username . " create new order");
            if(!$saveOrderDetail){
                return false;
            }
            return true;
        });
        if(!$save){
            return response()->json([
                "error" => true,
                "message" => "Somethhing went wrong."
            ],500);
        }
        return response()->json([
            "error" => false,
            "message" => "Data has been saved."
        ],200);
    }
}
