<?php

namespace Piclou\Ikcms\Facades;

use Illuminate\Support\Facades\Facade;

class Ikcms extends Facade
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

    /**
     * Upload des images
     * @param string $directory
     * @param $file
     * @return string
     */
    public function uploadImage(string $directory, $file): string
    {
        $directory = str_replace('\\', '/',$directory);

        $dir = config('ikcms.fileUploadFolder') . DIRECTORY_SEPARATOR .$directory;
        if(!file_exists($dir)){
            if(!mkdir($dir,0770, true)){
                dd('Echec lors de la création du répertoire : '.$dir);
            }
        }
        $fileName = $file->getClientOriginalName();
        $extension = $this->getExtension($fileName);

        $fileNewName = time().str_slug(str_replace(".".$extension,"",$fileName)).".".strtolower($extension);
        $file->move($dir,$fileNewName);
        $targetPath = $dir. "/" . $fileNewName;

        $imageManager =  new \Intervention\Image\ImageManager();
        $img = $imageManager->make($targetPath);
        $width = $img->width();
        if ($width > config('ikcms.imageMaxWidth')) {
            $img->resize( config('ikcms.imageMaxWidth'), null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($targetPath, config('ikcms.imageQuality'));
        }
        return $targetPath;
    }

    /**
     * Upload des fichiers
     * @param string $directory
     * @param $file
     * @return string
     */
    public function uploadFile(string $directory, $file): string
    {
        $directory = str_replace("\\","/",$directory);
        $file = str_replace("\\","/",$file);
        $dir = config('ikCommerce.fileUploadFolder') . "/" .$directory;
        if(!file_exists($dir)){
            if(!mkdir($dir,0770, true)){
                dd('Echec lors de la création du répertoire : '.$dir);
            }
        }
        $fileName = $file->getClientOriginalName();
        $extension = $this->getExtension($fileName);

        $fileNewName = time().str_slug(str_replace(".".$extension,"",$fileName)).".".strtolower($extension);
        $file->move($dir,$fileNewName);
        $targetPath = $dir. DIRECTORY_SEPARATOR . $fileNewName;;

        return $targetPath;

    }

    /**
     * @param string $str
     * @return string
     */
    public function getExtension(string $str): string
    {
        $i = strrpos($str, ".");
        if(!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        return substr($str, $i+1, $l);
    }


    /**
     * @param $modelName
     * @param $data
     * @return \Piclou\ikcms\Helpers\Translatable\FormTranslate
     */
    public function formTranslate($modelName, $data)
    {
        return(new \Piclou\ikcms\Helpers\Translatable\FormTranslate(
            $modelName,
            $data
        ));
    }


    /**
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatDate(string $date, string $format = 'd/m/Y'): string
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }

    public static function str_max(string $string, int $number, $after =  "...")
    {
        if(strlen($string) <= $number) {
            return $string;
        }
        return substr($string, 0 , $number) . $after;
    }

}
