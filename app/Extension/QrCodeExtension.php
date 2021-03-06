<?php

namespace App\Extension;

use Endroid\QrCode\QrCode as EndroidQrcode;

class QrCodeExtension extends \Twig_Extension
{
    const DEFAULT_IMAGE_SIZE = 200;
    const DEFAULT_PADDING = 0;

    /**
     * Surcharge la méthode getFilters
     * liée à l'interface Twig_ExtensionInterface
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'qrcode' => new \Twig_SimpleFilter(
                'qrcode', array($this, 'qrcode'), array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * @return string
     */
    public function qrcode(
        $value,
        $type = EndroidQrcode::IMAGE_TYPE_PNG,
        $size=self::DEFAULT_IMAGE_SIZE,
        $padding = self::DEFAULT_PADDING,
        $label = '',
        $version=null
        )
    {
        try {
            $qrCode = (new EndroidQrcode())
                ->setImageType($type)
                ->setLabel($label)
                ->setPadding((int) $padding)
                ->setSize((int) $size)
                ->setText($value)
                ->setVersion($version);
            $dataUrl = $qrCode->getDataUri();
        } catch (\Exception $e) {
            throw $e;
        }

        return $dataUrl;
    }

    /**
     * @return string
     */
    public static function qrcode_save(
        $name,
        $value,
        $type = EndroidQrcode::IMAGE_TYPE_PNG,
        $size=self::DEFAULT_IMAGE_SIZE,
        $padding = self::DEFAULT_PADDING,
        $label = '',
        $version=null
        )
    {
        try {
            $qrCode = (new EndroidQrcode())
                ->setImageType($type)
                ->setLabel($label)
                ->setPadding((int) $padding)
                ->setSize((int) $size)
                ->setText($value)
                ->setVersion($version)
                ->save('./img/qrcode_' . $name . '.png');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     */
    public static function delete_qrcode_save()
    {
        $dossier=opendir('./img');

        while ($fichier = readdir($dossier))
        {
            if (substr($fichier, 0, 7) === "qrcode_")
            {
                unlink('./img/' . $fichier);
            }
        }

        closedir($dossier);
    }

    public function getName()
    {
      return 'qrcode_extension';
    }
}
