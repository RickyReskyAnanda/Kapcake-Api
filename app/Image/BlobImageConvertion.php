<?php
namespace App\Image;

class BlobImageConvertion
{
  public static function image($gambar, $category="default"){
    if (!file_exists(storage_path('app/public/images/'.$category))) {
        mkdir(storage_path('app/public/images/'.$category), 0777, true);
    }
    $name = uniqid().time().uniqid().'.' . explode('/', explode(':', substr($gambar, 0, strpos($gambar, ';')))[1])[1];
    
    $path = storage_path('app/public/images/'.$category.'/').$name;
    $accessPath = 'storage/images/'.$category.'/'.$name;
    \Image::make($gambar)->save($path);

    $path2 = storage_path('app/public/images/'.$category.'/thumb_').$name;
    $accessPath2 = 'storage/images/'.$category.'/thumb_'.$name;
    \Image::make($gambar)->resize(250, 250, function($constraint) {
      $constraint->aspectRatio();
    })->save($path2);

    return  [
      [ 
        'path' => $path,
        'access_path' => $accessPath,
        'nama' => $name,
        'is_thumbnail' => 0
      ],
      [
        'path' => $path2,
        'access_path' => $accessPath2,
        'nama' => 'thumb_'.$name,
        'is_thumbnail' => 1
      ]
    ];
  }
}