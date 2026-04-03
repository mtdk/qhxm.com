<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserTb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * 生成用户ID
     *
     * @return string
     */
    private function generateUserId(): string
    {
        $microtime = substr(microtime(true), strpos(microtime(true), ".") + 1);
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $userid = "";
        
        for ($i = 0; $i < 6; $i++) {
            $userid .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $microtime . strtoupper(base_convert(time() - 1420070400, 10, 36)) . $userid;
    }

    /**
     * 显示注册表单
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $userId = $this->generateUserId();
        
        return view('auth.register', compact('userId'));
    }

    /**
     * 处理注册请求
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required|string|max:50',
            'username' => 'required|string|max:50',
            'userpwd' => 'required|string|min:6|max:16|confirmed',
            'userpwd_confirmation' => 'required|string|min:6|max:16',
        ], [
            'userid.required' => '用户ID不能为空',
            'username.required' => '用户名不能为空',
            'userpwd.required' => '用户密码不能为空',
            'userpwd.min' => '密码至少需要6个字符',
            'userpwd.max' => '密码最多16个字符',
            'userpwd.confirmed' => '两次输入的密码不一致',
            'userpwd_confirmation.required' => '请确认密码',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // 检查用户ID是否已存在
        $existingUser = UserTb::where('uid', $validated['userid'])->first();
        if ($existingUser) {
            return back()->withErrors(['userid' => '用户ID已存在'])->withInput();
        }

        // 创建新用户
        try {
            UserTb::create([
                'uid' => $validated['userid'],
                'uname' => $validated['username'],
                'upassword' => Hash::make($validated['userpwd']),
                'department_id' => 0, // 默认部门
                'role_id' => 2, // 默认角色：普通用户
                'userstate_id' => 1, // 默认状态：激活
            ]);

            return redirect()->route('login')
                ->with('success', '注册成功！请使用您的账号登录。')
                ->with('registered_userid', $validated['userid']);
        } catch (\Exception $e) {
            return back()->with('error', '注册失败：' . $e->getMessage())->withInput();
        }
    }

    /**
     * 验证用户ID是否可用（AJAX接口）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserId(Request $request)
    {
        $request->validate([
            'userid' => 'required|string|max:50',
        ]);

        $exists = UserTb::where('uid', $request->input('userid'))->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '用户ID已存在' : '用户ID可用',
        ]);
    }

    /**
     * 验证密码强度（AJAX接口）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPasswordStrength(Request $request)
    {
        $password = $request->input('password', '');
        
        $strength = 0;
        $messages = [];
        
        // 长度检查
        if (strlen($password) >= 8) {
            $strength += 1;
        } else {
            $messages[] = '密码长度至少8个字符';
        }
        
        // 包含数字
        if (preg_match('/\d/', $password)) {
            $strength += 1;
        } else {
            $messages[] = '密码应包含数字';
        }
        
        // 包含小写字母
        if (preg_match('/[a-z]/', $password)) {
            $strength += 1;
        } else {
            $messages[] = '密码应包含小写字母';
        }
        
        // 包含大写字母
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 1;
        } else {
            $messages[] = '密码应包含大写字母';
        }
        
        // 包含特殊字符
        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $strength += 1;
        } else {
            $messages[] = '密码应包含特殊字符';
        }
        
        // 评估强度
        if ($strength >= 4) {
            $level = '强';
            $color = 'success';
        } elseif ($strength >= 3) {
            $level = '中等';
            $color = 'warning';
        } else {
            $level = '弱';
            $color = 'danger';
        }
        
        return response()->json([
            'strength' => $strength,
            'level' => $level,
            'color' => $color,
            'messages' => $messages,
            'percentage' => ($strength / 5) * 100,
        ]);
    }

    /**
     * 获取注册统计信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $totalUsers = UserTb::count();
        $activeUsers = UserTb::where('userstate_id', 1)->count();
        $inactiveUsers = UserTb::where('userstate_id', '!=', 1)->count();
        
        // 按角色统计
        $adminUsers = UserTb::where('role_id', 1)->count();
        $normalUsers = UserTb::where('role_id', 2)->count();
        
        // 最近一周注册用户数
        $recentUsers = UserTb::where('created_at', '>=', now()->subWeek())->count();

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'admin_users' => $adminUsers,
            'normal_users' => $normalUsers,
            'recent_users' => $recentUsers,
        ]);
    }
}