<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppUsers extends Authenticatable
{
    use HasFactory;

    const UPDATED_AT = "updated_at";
    const CREATED_AT = "created_at";
    protected $table = "bh_app_users";
    protected $primaryKey = "Id";
    protected $fillable = [
        'users_uid',
        'user_uid',
        "app_name",
        "users",
        'uid',
        'type_uid',
        'app_uid',
        'status',
        'name',
        'type',
        'user',
        'password',
        'uuid',
        'time',
        'add_km',
        'recharge',
        'last_recharge',
        'end_time',
        'features',
        'ip',
        'last_time',
        "activate_time",
        "activate_date",
        'last_ip',
        'login_count',
        'remark',
        'ty_remark',
        'login_count',
        'token',
        'email_verified_at',
        "unbind_count"
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function createds($arrays)
    {
        return (new AppUsers())->addAll($arrays);
    }

    public function addAll(array $data)
    {
        return DB::table($this->getTable())->insert($data);
    }

    public static function updateds(array $array_pop_key, string $label = "uid")
    {
        if (isset($array_pop_key['password']))
            $array_pop_key['password'] = Hash::make($array_pop_key['password']);
        $App = strpos($array_pop_key[$label], "]") > 0 ? AppUsers::whereIn($label, json_decode($array_pop_key[$label], true)) : AppUsers::where($label, "=", $array_pop_key['uid']);
        return $App->update(Utils::array_pop_key($array_pop_key, $label));
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
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
