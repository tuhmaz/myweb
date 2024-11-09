<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadController extends Controller
{
    // رفع الصور
    public function upload(Request $request)
    {
        // التحقق من وجود ملف في الطلب
        if ($request->hasFile('file')) {
            // الحصول على الملف المرفوع
            $file = $request->file('file');

            // التحقق من أن الملف صورة صالحة
            if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'])) {
                return response()->json(['error' => 'Invalid image file.'], 400);
            }

            // إنشاء اسم عشوائي للملف بصيغة WebP
            $filename = Str::random(10) . '.webp';

            // إنشاء نسخة من الصورة باستخدام Intervention Image
            $image = Image::make($file->getRealPath());

            // تحديد الجودة (اختياري)
            $quality = 70; // القيمة من 0 إلى 100

            // تحويل الصورة إلى صيغة WebP
            $image->encode('webp', $quality);

            // حفظ الصورة في مجلد 'public/images'
            Storage::disk('public')->put('images/' . $filename, (string) $image);

            // إرجاع الرابط إلى الصورة المرفوعة
            return response()->json(['url' => Storage::url('images/' . $filename)], 201);
        }

        // إرجاع خطأ إذا لم يتم العثور على ملف في الطلب
        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    // رفع الملفات العامة (مثل PDF و DOCX)
    public function uploadFile(Request $request)
    {
        // التحقق من وجود ملف في الطلب
        if ($request->hasFile('file')) {
            // الحصول على الملف المرفوع
            $file = $request->file('file');

            // إنشاء اسم عشوائي للملف باستخدام الامتداد الأصلي
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();

            // تخزين الملف في مجلد 'public/files' والحصول على مسار التخزين
            $path = $file->storeAs('public/files', $filename);

            // إرجاع الرابط إلى الملف المرفوع
            return response()->json(['url' => Storage::url('files/' . $filename)], 201);
        }

        // إرجاع خطأ إذا لم يتم العثور على ملف في الطلب
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
