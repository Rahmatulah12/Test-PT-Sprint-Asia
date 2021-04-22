<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\LogActivity as log;
use App\Models\CategoryMenu;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MenuController extends BaseController
{
    private $menu, $categoryMenu;

    public function __construct()
    {
        $this->menu = new Menu();
        $this->categoryMenu = new CategoryMenu();
    }

    public function index(Request $request)
    {
        $menus = null;
        if(count($request->input()) <= 0)
        {
            $menus = $this->menu->getAllData();
        } else {
            $menus = $this->menu->getAllData((int)$request->input('size'), $request->input('keyword'));
        }
        log::addToLog(auth()->user()->username . " Access Menu");
        return response()->json([
            "error" => false,
            "data" => $menus,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = [
            "category_menu" => $request->category_menu,
            "name" => htmlspecialchars(strtolower($request->name)),
            "description" => htmlspecialchars(strtolower($request->description)),
        ];

        $validator = Validator::make($data, [
            'category_menu' => 'required',
            'name' => 'required|unique:category_menus',
        ]);

        // check validation
        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 400);
        }

        $save = false;
        $save = DB::transaction(function () use($data){
            $this->menu->category_menu_id = $data['category_menu'];
            $this->menu->name = $data['name'];
            $this->menu->description = $data['description'];
            $this->menu->save();
            log::addToLog(auth()->user()->username . " create new menu");
            return true;
        });
        if(!$save){
            return response()->json([
                'error' => true,
                'message' => "Something went wrong."
            ], 500);
        }
        return response()->json([
            "error" => false,
            "message" => "Data has been saved."
        ],200);
    }

    public function update(Request $request)
    {
        $data = [
            "id" => $request->id,
            "category_menu" => $request->category_menu,
            "name" => htmlspecialchars(strtolower($request->name)),
            "description" => htmlspecialchars(strtolower($request->description)),
        ];

        $validator = Validator::make($data, [
            "id" => "required",
            'category_menu' => 'required',
            'name' => 'required|unique:category_menus,id',
        ]);

        // check validation
        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 400);
        }

        $save = false;
        $save = DB::transaction(function () use($data){
            $menu = $this->menu->find($data['id']);
            $menu->category_menu_id = $data['category_menu'];
            $menu->name = $data["name"];
            $menu->description = $data['description'];
            $menu->save();
            log::addToLog(auth()->user()->username . " update new menu");
            return true;
        });
        if(!$save){
            return response()->json([
                'error' => true,
                'message' => "Something went wrong."
            ], 500);
        }
        return response()->json([
            "error" => false,
            "message" => "Data has been saved."
        ],200);
    }

    public function show($id){
        $menu = $this->menu->find($id);
        if(!$menu){
            return response()->json([
                "error" => false,
                "message" => "Data not found.",
            ], 200);
        }
        return response()->json([
            "error" => false,
            "data" => $menu,
        ], 200);
    }

    public function delete($id)
    {
        $menu = $this->menu->find($id);
        if(!$menu)
        {
            return response()->json([
                "error" => true,
                "message" => "Can not delete data, because data is not found.",
            ], 400);
        }
        $menu->deleted_at = date("Y-m-d H:i:s");
        $menu->save();
        if(!$menu){
            return response()->json([
                "error" => true,
                "message" => "Something went wrong.",
            ], 500);
        }
        return response()->json([
            "error" => false,
            "data" => "Data has been deleted.",
        ], 200);
    }
}
