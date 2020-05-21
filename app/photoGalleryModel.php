<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class photoGalleryModel extends Model
{
    protected $table    = 'images_path';                        // テーブルを指定
    protected $fillable = ['user_id', 'original_name', 'path']; // ホワイトリスト
}
