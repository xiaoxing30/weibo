<?php
/**
 * 会话控制器，该控制器将用于处理用户登录退出相关的操作。
 * 可以把会话理解为资源，当用户登录成功时，会话将被创建；当用户退出登录时，会话会被销毁。
 * 只是在这里会话并不会保存到数据库中，而是保存在浏览器上。
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        //Auth::attempt() 方法可接收两个参数，第一个参数为需要进行用户身份认证的数组，第二个参数为是否为用户开启『记住我』功能的布尔值。
        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success', '欢迎回来');
            // Laravel 提供的 Auth::user() 方法来获取 当前登录用户 的信息，这里将数据传送给路由。
            $fallback = route('users.show', Auth::user());
            //将用户重定向到他之前尝试访问的页面
            //redirect() 实例提供了一个 intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上，并接收一个默认跳转地址参数，当上一次请求记录为空时，跳转到默认地址上。
            return redirect()->intended($fallback);
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        //Laravel 默认提供的 Auth::logout() 方法来实现用户的退出功能。
        Auth::logout();
        session()->flash('success', '您已成功登录！');
        return redirect('login');
    }
}
