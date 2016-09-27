<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/13/16
 * Time: 7:56 AM
 */

namespace Core\MediaBundle\Service;


use Core\CoreBundle\CoreApp\Util;
use Core\MediaBundle\Document\Media;
use Symfony\Component\DependencyInjection\Container;

class MediaService
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Media|null $image
     * @param $size
     * @return bool|string
     */
    public function generateThumbnail(Media $image, $size)
    {
        return $this->resizeImage($image, $size);
    }

    /**
     * @param Media $media
     * @param integer $size
     * @return bool|string
     */
    public function resizeImage(Media $media, $size)
    {
        $mediaParams    = $this->container->getParameter('media');
        $size           = (abs(intval($size)) > 0) ? $size : $mediaParams['thumbnail']['size'];

        if(!file_exists($mediaParams['dir'])) {
            mkdir($mediaParams['dir'], 0755, true);
        }

        $newWidth       = $size;
        $newHeight      = $size;
        $newFileName    = "{$media->getId()}-@{$size}.{$media->getExtension()}";
        $filePath       = $mediaParams['dir'] . '/' . $newFileName;

        if (!is_file($filePath)) {
            if (false !== strpos(strtolower($media->getMimeType()), 'image')) {
                $src_image = imagecreatefromstring($media->getFile()->getBytes());

                if (false !== $src_image) {
                    $mimeType   = strtolower($media->getMimeType());
                    $oldWidth   = imagesx($src_image);
                    $oldHeight  = imagesy($src_image);

                    // Scale based on height alone
                    if($oldHeight > $oldWidth) {
                        $newWidth   = $oldWidth * ($newWidth / $oldHeight);
                        $newHeight  = $size;
                    } // Scale based on width
                    else if($oldWidth > $oldHeight) {
                        $newWidth   = $size;
                        $newHeight  = $oldHeight * ($newHeight/$oldWidth);
                    }

                    $des_image  = imagecreatetruecolor($newWidth, $newHeight);

                    // Preserve transparency
                    if('image/png' === $mimeType || 'image/gif' === $mimeType) {
                        imagecolortransparent($des_image, imagecolorallocatealpha($des_image,0,0,0,127));
                        imagealphablending($des_image, false);
                        imagesavealpha($des_image, true);
                    }

                    imagecopyresampled($des_image, $src_image, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

                    if ('image/jpeg' === $mimeType || 'image/jpg' === $mimeType) {
                        imagejpeg($des_image, $filePath, intval($mediaParams['thumbnail']['quality']));
                    }

                    if ('image/png' === $mimeType) {
                        imagepng($des_image, $filePath, Util::getInRange($mediaParams['thumbnail']['quality']/10, 0, 9));
                    }

                    if ('image/gif' === $mimeType) {
                        imagegif($des_image, $filePath);
                    }

                    imagedestroy($des_image);
                }
                else {
                    return false;
                }

                imagedestroy($src_image);
            }
            else if (false !== strpos(strtolower($media->getMimeType()), 'audio')) {
                $filePath = $this->container->getParameter('kernel.root_dir')."/../web/assets/image/audio.jpg";
            }
            else if (false !== strpos(strtolower($media->getMimeType()), 'video')) {
                $filePath = $this->container->getParameter('kernel.root_dir')."/../web/assets/image/video.jpg";
            }
            else if (
                $media->getExtension() == 'rar' ||
                $media->getExtension() == 'zip' ||
                $media->getExtension() == '7z' ||
                $media->getExtension() == 'gz')
            {
                $filePath = $this->container->getParameter('kernel.root_dir')."/../web/assets/image/archive.jpg";
            }
            else if (
                $media->getExtension() == 'php' ||
                $media->getExtension() == 'css' ||
                $media->getExtension() == 'js' ||
                $media->getExtension() == 'twig' ||
                $media->getExtension() == 'html' ||
                $media->getExtension() == 'htm' ||
                $media->getExtension() == 'xhtml' ||
                $media->getExtension() == 'json')
            {
                $filePath = $this->container->getParameter('kernel.root_dir')."/../web/assets/image/code.jpg";
            }
            else {
                $filePath = $this->container->getParameter('kernel.root_dir')."/../web/assets/image/default.jpg";
            }
        }

        return $filePath;
    }
}