<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable  //Authenticatable 是授权相关功能的引用
{
    //Notifiable 是消息通知相关功能引用
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];//为了提高应用的安全性，Laravel 在用户模型中默认为我们添加了 fillable 在过滤用户提交的字段，只有包含在该属性中的字段才能够被正常更新：

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];//对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
     * 该方法主要做了以下几个操作：
     * 1.为 gravatar 方法传递的参数 size 指定了默认值 100；
     * 2.通过 $this->attributes['email'] 获取到用户的邮箱；
     * 3.使用 trim 方法剔除邮箱的前后空白内容；
     * 4.用 strtolower 方法将邮箱转换为小写；
     * 5.将小写的邮箱使用 md5 方法进行转码；
     * 6.将转码后的邮箱与链接、尺寸拼接成完整的 URL 并返回；
     * */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

}
