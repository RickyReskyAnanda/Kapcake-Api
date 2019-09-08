<?php
namespace App\Image;
use Image;

class BlobImageConvertion
{
  public static function image($gambar, $category="default"){
    if (!file_exists(public_path('images/'.$category))) {
        mkdir(public_path('images/'.$category), 0777, true);
    }
    $name = uniqid().time().uniqid().'.' . explode('/', explode(':', substr($gambar, 0, strpos($gambar, ';')))[1])[1];
    
    $path = public_path('images/'.$category.'/').$name;
    $accessPath = 'images/'.$category.'/'.$name;
    $link1 = asset('images/'.$category.'/thumb_'.$name);
    $img = \Image::make($gambar);

    $height = 0;
    $width = 0;

    if($img->height() > $img->width()){
      $width = (int)$img->width();
      $height = (int)(($width * 3) / 4);
    }
    elseif($img->width() > $img->height()){
      $height = (int)$img->height();
      $width = (int)(($height * 4) /3);
    }
    $img->crop($width, $height);
    $img->resize(350, 280, function ($constraint) {
      $constraint->aspectRatio();
    });

    $img->save($path);

    $path2 = public_path('images/'.$category.'/thumb_').$name;
    $accessPath2 = 'images/'.$category.'/thumb_'.$name;
    $link2 = asset('images/'.$category.'/thumb_'.$name);

    $img2 = \Image::make($gambar);
    $img2->crop($width, $height);
    $img2->resize(200, 160, function ($constraint) {
      $constraint->aspectRatio();
    });
    $img2->save($path2);

    return  [
      [ 
        'path' => $path,
        'access_path' => $accessPath,
        'nama' => $name,
        'link' => $link1,
        'is_thumbnail' => 0
      ],
      [
        'path' => $path2,
        'access_path' => $accessPath2,
        'link' => $link2,
        'nama' => 'thumb_'.$name,
        'is_thumbnail' => 1
      ]
    ];
  }
}
