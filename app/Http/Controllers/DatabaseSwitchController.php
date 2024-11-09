<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DatabaseSwitchController extends Controller
{
    public function switchDatabase(Request $request)
    {
        // الحصول على اسم الاتصال بقاعدة البيانات من الطلب
        $dbConnection = $request->input('db_connection');

        // تخزين اسم الاتصال في الجلسة
        Session::put('db_connection', $dbConnection);

        // إعادة توجيه المستخدم إلى الصفحة الرئيسية أو أي صفحة أخرى بعد التبديل
        return redirect()->route('home')->with('status', 'Database switched to ' . $dbConnection);
    }
}
