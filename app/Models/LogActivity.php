<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogActivity extends Model
{
    protected $fillable = [
        'subject', 'url', 'method', 'ip', 'agent', 'user_id'
    ];

    public static function addToLog($subject)
    {
    	$log = DB::table("log_activities")->insert([
            "subject" => $subject,
            "url" => request()->fullUrl(),
            "method" => request()->method(),
            "ip" => request()->ip(),
            "agent" => request()->header("user-agent"),
            "user_id" => auth()->check()? auth()->user()->id : "0",
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
        return $log;
    }
}
