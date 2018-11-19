<?php

namespace Piclou\Ikcms;

class Ikcms
{
    public function resizeImage($img, $width = false, $height = false, string $direction = 'center'): string
    {
        if(is_null($img) || empty($img)){
            return '/'. config('ikcms.imageNotFound');
        }
        $dir = config('ikcms.imageCacheFolder');
        $infos = pathinfo($img);
        $fileName = $infos['filename'];
        $extension = $infos['extension'];
        $dir .= '/' . $infos['dirname'];

        if (!file_exists($infos['dirname']. '/' . $fileName . "." .  $infos['extension'])) {
            return '/'. config('ikcms.imageNotFound');
        }
        if(!file_exists($dir)){
            if(!mkdir($dir,0770, true)){
                dd('Echec lors de la création du répertoire : '.$dir);
            }
        }

        if ($width && $height) {
            $cacheResize = "_".$width."_".$height;
        } elseif ($width && !$height) {
            $cacheResize = "_".$width;
        } else {
            $cacheResize = "_".$height;
        }

        if (file_exists(public_path() . "/" . $dir . "/" . $fileName.$cacheResize.".".$extension)) {
            return asset($dir. "/" . $fileName.$cacheResize.".".$extension);
        } else {
            $manager = new \Intervention\Image\ImageManager(['drive' => 'gd']);
            $image = $manager->make($img);
            if ($width && $height) {
                $image->fit($width, $height, function () {
                }, $direction);
            } elseif ($width && !$height) {
                $image->fit($width, null, function () {
                }, $direction);
            } else {
                $image->resize(null, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->save(
                $dir . "/" . $fileName.$cacheResize.".".$extension,
                config('ikcms.imageQuality')
            );
            return "/".$dir . "/" . $fileName.$cacheResize.".".$extension;
        }
    }
}