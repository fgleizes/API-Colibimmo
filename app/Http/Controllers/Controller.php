<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $imgWhiteList = ["jpeg","png","jpg","gif","svg"];
    /**
     * Méthode permettant d'uploader une image avec : 
     * - le nétoyage du nom
     * - le retour du chemin de l'image
     */
    protected function uploadImage($folder, $file, $replaceImage = false)
    {
        // $this->media .= $folder;
        // $media = storage_path($this->media);
        $fileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();

        $fileName = str_replace('.' . $fileExtension, '', $fileName);
        $fileName = str_replace(' ', '_', $fileName);
        $fileName = str_replace('.', '_', $fileName);
        $fileName = str_replace('__', '_', $fileName);
        $fileName .= time();

        if (!in_array(strtolower($fileExtension), $this->imgWhiteList)) {
            return response()->json(['error' => 'File type not allowed'], 400);
        }
        if ($file->move($folder, $fileName . '.' . $fileExtension)) {
            $this->resize_image($folder . '/' . $fileName . '.' . $fileExtension, 300, 300);
        } else {
            return response()->json(['error' => 'Cannot upload file'], 400);
        }
        if ($replaceImage) {
            $this->deleteImage($replaceImage);
        }
        return ['folder' => $folder, 'filename' =>  $fileName . '.' . $fileExtension];
    }

    /**
     * Méthode permettant de supprimer une image
     */
    protected function deleteImage($fileName)
    {
        return unlink(storage_path($fileName));
    }

    protected function resize_image($file, $w, $h, $crop = FALSE)
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }

        $source_url_parts = pathinfo($file);
        $folder = $source_url_parts['dirname'];
        $fileName = $source_url_parts['filename'];
        $fileExtension = $source_url_parts['extension'];
        $fileName = str_replace('.' . $fileExtension, '', $fileName);
        $fileName .= '-resized.' . $fileExtension;

        if($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'JPG' || $fileExtension == 'JPEG') {
            $src = imagecreatefromjpeg($file);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagejpeg($dst, $folder . '/' . $fileName);
        } elseif ($fileExtension == 'png' || $fileExtension == 'PNG') {
            $src = imagecreatefrompng($file);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagepng($dst, $folder . '/' . $fileName);
        } elseif ($fileExtension == 'gif' || $fileExtension == 'GIF') {
            $src = imagecreatefromgif($file);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagegif($dst, $folder . '/' . $fileName);
        }
        // return $dst;
    }
}
