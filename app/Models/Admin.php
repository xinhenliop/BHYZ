<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory;

    const UPDATED_AT = "updated_at";
    const CREATED_AT = "created_at";
    protected $table = "bh_admin";
    protected $primaryKey = "Id";
    protected $fillable = [
        'user',
        "uid",
        'password',
        'admin_system',
        'status',
        'last_login_time',
        'last_login_ip',
        "root",
        'login_count'
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

    public static function updateds(array $array_pop_key)
    {
        $App = strpos($array_pop_key['uid'], "]") > 0 ? Admin::whereIn("uid", json_decode($array_pop_key['uid'], true)) : Admin::where("uid", "=", $array_pop_key['uid']);
        return $App->update(Utils::array_pop_key($array_pop_key, "uid"));
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
