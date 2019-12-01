<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        //Laravel 提供身份验证（Auth）中间件来过滤 未登录用户 的 动作。
        //except 方法来设定 指定动作 不使用 Auth 中间件进行过滤，意为 —— 除了此处指定的动作以外，所有其他动作都必须登录用户才能访问，类似于黑名单的过滤机制
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index','confirmEmail']
        ]);

        //Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作。
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

   /*
    * Laravel 会自动解析定义在控制器方法（变量名匹配路由片段）中的 Eloquent 模型类型声明。
    * 在下面代码中，由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user 会匹配路由片段中的 {user}，
    * 这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
    * 此功能称为 『隐性路由模型绑定』，是『约定优于配置』设计范式的体现，同时满足以下两种情况，此功能即会自动启用：
    * 1.路由声明时必须使用 Eloquent 模型的单数小写格式来作为路由片段参数，User 对应 {user}：
    *     Route::get('/users/{user}', 'UsersController@show')->name('users.show');
    * 在使用资源路由 Route::resource('users', 'UsersController'); 时，默认已经包含了上面的声明。
    * 2.控制器方法传参中必须包含对应的 Eloquent 模型类型声明，并且是有序的：
    *     public function show(User $user)
    * 满足以上两个条件时，Laravel 将会自动查找 ID 为 {user}处的值 的用户并赋值到变量 $user 中，如果数据库中找不到对应的模型实例，会自动生成 HTTP 404 响应。
    * */
    public function show(User $user)
    {
        //将用户对象 $user 通过 compact 方法转化为一个关联数组，
        //并作为第二个参数传递给 view 方法，将数据与视图进行绑定。
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        //用户模型 User::create() 创建成功后会返回一个用户对象，并包含新注册用户的所有信息
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //让一个已认证通过的用户实例进行登录，可以使用以下方法：
        //Auth::login($user);
        $this->sendEmailConfirmationTo($user);

        //Laravel 提供了一种用于临时保存用户数据的方法 - 会话（Session），并附带支持多种会话后端驱动，可通过统一的 API 进行使用。
        //使用 session() 方法来访问会话实例。
        //当想存入一条缓存的数据，让它只在下一次的请求内有效时，则可以使用 flash 方法。
        //flash 方法接收两个参数，第一个为会话的键，第二个为会话的值，可以通过下面这行代码的为会话赋值。
        //之后可以使用 session()->get('success') 通过键名来取出对应会话中的数据，取出的结果为：
        //  欢迎，您将在这里开启一段新的旅程~。
        session()->flash('success', '验证邮箱已发送到你的注册邮箱上，请注意查收。');

        //这里是一个『约定优于配置』的体现，
        //此时 $user 是 User 模型对象的实例。
        //route() 方法会自动获取 Model 的主键，也就是数据表 users 的主键 id，以上代码等同于：
        //  redirect()->route('users.show', [$user->id]);
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        //授权策略定义完成之后，我们便可以通过在用户控制器中使用 authorize 方法来验证用户授权策略。
        //默认的 App\Http\Controllers\Controller 类包含了 Laravel 的 AuthorizesRequests trait。
        //此 trait 提供了 authorize 方法，它可以被用于快速授权一个指定的行为，当无权限运行该行为时会抛出 HttpException。
        //authorize 方法接收两个参数，第一个为授权策略的名称，第二个为进行授权验证的数据。

        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        //使用 authorize 方法来对删除操作进行授权验证。
        //在删除动作的授权中，我们规定只有当前用户为管理员，且被删除用户不是自己时，授权才能通过。
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'summer@example.com';
        $name = 'Summer';
        $to = $user->email;
        $subject = '感谢注册 Weibo 应用！请确认你的邮箱。';

        Mail::send($view,$data,function ($message) use ($from,$name,$to,$subject){
           $message->from($from,$name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功！');
        return redirect()->route('users.show',[$user]);
    }

}
