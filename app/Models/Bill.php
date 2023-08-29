<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public const UPDATED_AT = "updated_at";
    public const CREATED_AT = "created_at";
    protected $primaryKey = 'Id';
    protected $table = 'bh_bill';
    protected $hidden = [];
    protected $fillable = [
        "uid",
        "price",
        "user",
        "user_uid",
        "type",
        "message",
    ];

    public static function updateds(array $array_pop_key)
    {
        $App = strpos($array_pop_key['uid'], "]") > 0 ? Admin::whereIn("uid", json_decode($array_pop_key['uid'], true)) : Admin::where("uid", "=", $array_pop_key['uid']);
        return $App->update(Utils::array_pop_key($array_pop_key, "uid"));
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
