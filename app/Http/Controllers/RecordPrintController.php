<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserTb;

class RecordPrintController extends Controller
{
    /**
     * 分散设备记录打印
     */
    public function fssb(Request $request)
    {
        // 获取当前登录用户
        $user = UserTb::where('uid', session('uid'))->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', '请先登录');
        }
        
        // 获取分散设备数据
        $records = DB::table('fssbjl')
            ->select('*')
            ->orderBy('create_time', 'desc')
            ->limit(50)
            ->get();
        
        return view('record-print.fssb', [
            'user' => $user,
            'records' => $records,
            'page_title' => '分散设备记录打印',
        ]);
    }
    
    /**
     * 研磨设备记录打印
     */
    public function ymsb(Request $request)
    {
        // 获取当前登录用户
        $user = UserTb::where('uid', session('uid'))->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', '请先登录');
        }
        
        // 获取研磨设备数据
        $records = DB::table('ymsbjl')
            ->select('*')
            ->orderBy('create_time', 'desc')
            ->limit(50)
            ->get();
        
        return view('record-print.ymsb', [
            'user' => $user,
            'records' => $records,
            'page_title' => '研磨设备记录打印',
        ]);
    }
    
    /**
     * 空压机记录打印
     */
    public function kyjsb(Request $request)
    {
        // 获取当前登录用户
        $user = UserTb::where('uid', session('uid'))->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', '请先登录');
        }
        
        // 获取空压机数据
        $records = DB::table('kyjsbjl')
            ->select('*')
            ->orderBy('create_time', 'desc')
            ->limit(50)
            ->get();
        
        return view('record-print.kyjsb', [
            'user' => $user,
            'records' => $records,
            'page_title' => '空压机记录打印',
        ]);
    }
    
    /**
     * 冰水机记录打印
     */
    public function bsjsb(Request $request)
    {
        // 获取当前登录用户
        $user = UserTb::where('uid', session('uid'))->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', '请先登录');
        }
        
        // 获取冰水机数据
        $records = DB::table('bsjsbjl')
            ->select('*')
            ->orderBy('create_time', 'desc')
            ->limit(50)
            ->get();
        
        return view('record-print.bsjsb', [
            'user' => $user,
            'records' => $records,
            'page_title' => '冰水机记录打印',
        ]);
    }
    
    /**
     * 废气设备记录打印
     */
    public function fqsb(Request $request)
    {
        // 获取当前登录用户
        $user = UserTb::where('uid', session('uid'))->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', '请先登录');
        }
        
        // 获取废气设备数据
        $records = DB::table('fqpfsbjl')
            ->select('*')
            ->orderBy('create_time', 'desc')
            ->limit(50)
            ->get();
        
        return view('record-print.fqsb', [
            'user' => $user,
            'records' => $records,
            'page_title' => '废气设备记录打印',
        ]);
    }
}
