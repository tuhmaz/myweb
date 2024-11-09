<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\News;
use App\Models\Category;

class NewsSeeder extends Seeder
{
    public function run()
    {
        // جلب جميع الفئات المتاحة
        $categories = Category::all();

        foreach ($categories as $category) {
            // لكل فئة، إنشاء 4 أخبار مكررة بإجمالي 40 خبر (10 فئات * 4 أخبار)
            for ($i = 0; $i < 4; $i++) {
                News::create([
                    'title' => "خبر عنوان للفئة {$category->name} - رقم " . ($i + 1),
                    'description' => "هذا هو الوصف الخاص بالخبر للفئة {$category->name} - رقم " . ($i + 1),
                    'category_id' => $category->id,
                    'meta_description' => Str::limit("هذا هو الوصف الخاص بالخبر للفئة {$category->name} - رقم " . ($i + 1), 160),
                    'image' => 'noimage.svg',
                    'alt' => "صورة خبر الفئة {$category->name}",
                ]);
            }
        }
    }
}
