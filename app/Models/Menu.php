<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'category_menu_id', 'name', 'description', 'status',
    ];

    public function categoryMenu()
    {
        return $this->belongsTo("App\Models\CategoryMenu");
    }

    public function orderDetails(){
        return $this->hasMany("App\Models\OrderDetail");
    }

    public function getAllData($size = 10, $keyword = null){
        $menus = $this->where('name', 'like', "%$keyword%")->orWhere('description', 'like', "%$keyword%")->where('deleted_at', null)
        ->paginate($size);
        return $menus;
    }
}
