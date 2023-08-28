<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    public const UPDATED_AT = "updated_at";
    public const CREATED_AT = "created_at";
    protected $primaryKey = "Id";
    protected $table = "bh_logs";
    protected $fillable = [
        "uid",
        "log_level",
        "log_msg",
        "log_users"
    ];

    public static function createds($level, $msg, $users)
    {
        $thiss = new Logs();
        $thiss->uid = rand(111111, 99999999);
        $thiss->log_level = $level;
        $thiss->log_msg = $msg;
        $thiss->log_users = $users;
        $thiss->save();
    }

    public function show()
    {
        $show = [];
        $label = $this->fillable;
        array_push($label, "created_at", "updated_at");
        foreach (array_diff($label, $this->hidden) as $column => $value) $show[$value] = $this->$value;
        return $show;
    }
}
