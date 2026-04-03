<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserTb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * 显示登录表单
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // 获取激活状态的用户列表
        $users = UserTb::where('userstate_id', 1)->get(['uid', 'uname']);
        
        return view('auth.login', [
            'users' => $users,
            'rememberedUid' => Cookie::get('remeber_myid'),
            'rememberedPassword' => Cookie::get('uspasswd'),
        ]);
    }

    /**
     * 处理登录请求
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
            'upassword' => 'required|string',
        ]);

        $uid = $request->input('uid');
        $password = $request->input('upassword');
        $remember = $request->boolean('remember_me', false);

        // 查找用户
        $user = UserTb::where('uid', $uid)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'uid' => ['用户信息不存在'],
            ]);
        }

        // 验证密码 - 使用password_verify兼容现有哈希
        if (!Hash::check($password, $user->upassword)) {
            throw ValidationException::withMessages([
                'upassword' => ['用户密码错误'],
            ]);
        }

        // 检查用户状态
        if ($user->userstate_id != 1) {
            throw ValidationException::withMessages([
                'uid' => ['用户账户已被禁用'],
            ]);
        }

        // 登录用户
        Auth::login($user, $remember);

        // 处理"记住我"cookie
        if ($remember) {
            Cookie::queue('remeber_myid', $uid, 60 * 24); // 24小时
            Cookie::queue('uspasswd', $password, 60 * 24);
        } else {
            Cookie::queue(Cookie::forget('remeber_myid'));
            Cookie::queue(Cookie::forget('uspasswd'));
        }

        // 记录用户部门等信息到session
        session([
            'uid' => $user->uid,
            'uname' => $user->uname,
            'department_id' => $user->department_id,
            'role_id' => $user->role_id,
        ]);

        return redirect()->intended(route('home'));
    }

    /**
     * 处理注销请求
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * 检查记住的用户信息（AJAX接口）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRememberedUser(Request $request)
    {
        $uid = $request->input('uid');
        $rememberedUid = Cookie::get('remeber_myid');
        $rememberedPassword = Cookie::get('uspasswd');

        if ($uid === $rememberedUid && $rememberedPassword) {
            return response()->json([
                'password' => $rememberedPassword,
                'remembered' => true,
            ]);
        }

        return response()->json([
            'password' => '',
            'remembered' => false,
        ]);
    }
}
