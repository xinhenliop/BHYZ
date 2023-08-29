<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kami extends Model
{
    use HasFactory;

    public const UPDATED_AT = "updated_at";
    public const CREATED_AT = "created_at";
    protected $primaryKey = "Id";
    protected $hidden = [

    ];
    protected $fillable = [
        "uid",
        "type",
        "time",
        "price",
        "status",
        "card_str",
        "app_uid",
        "app",
        "remark",
        "prefix",
        "suffix",
        "length",
        "type_time"
    ];
    protected $table = "bh_km_type";

    public static function createds($arrays)
    {
        $thiss = new Kami();
        $thiss->uid = rand(111111, 99999999);
        if (empty($arrays["card_str"])) {
            $arrays = Utils::array_pop_key($arrays, "card_str");
        }
        foreach ($arrays as $key => $value) {
            $thiss->{$key} = $value;
        }
        return $thiss->save();
    }

    public static function updateds(array $array_pop_key)
    {
        $App = strpos($array_pop_key['uid'], "]") > 0 ? Kami::whereIn("uid", json_decode($array_pop_key['uid'], true)) : Kami::where("uid", "=", $array_pop_key['uid']);
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
