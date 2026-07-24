<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PaymentProofAnalyzer
{
    private const PROVIDERS = [
        'dana' => 'DANA',
        'bni' => 'BNI',
        'bca' => 'BCA',
        'bri' => 'BRI',
        'mandiri' => 'Mandiri',
        'ovo' => 'OVO',
        'gopay' => 'GoPay',
        'go-pay' => 'GoPay',
        'shopeepay' => 'ShopeePay',
        'seabank' => 'SeaBank',
        'btn' => 'BTN',
        'cimb' => 'CIMB Niaga',
        'permata' => 'PermataBank',
        'linkaja' => 'LinkAja',
    ];

    private const EDITOR_KEYWORDS = [
        'photoshop',
        'canva',
        'picsart',
        'snapseed',
        'lightroom',
        'pixlr',
        'capcut',
        'editor',
    ];

    public function analyze(UploadedFile $file): array
    {
        $flags = [];
        $originalName = $file->getClientOriginalName();
        $searchableName = Str::lower($originalName);
        $provider = null;
        $confidence = 35;

        foreach (self::PROVIDERS as $needle => $label) {
            if (str_contains($searchableName, $needle)) {
                $provider = $label;
                $confidence = 78;
                break;
            }
        }

        $size = (int) $file->getSize();
        if ($size < 25 * 1024) {
            $flags[] = 'file_too_small';
        } elseif ($size > 1800 * 1024) {
            $flags[] = 'file_large';
        }

        $dimensions = @getimagesize($file->getRealPath());
        if ($dimensions) {
            [$width, $height] = $dimensions;
            if ($width < 360 || $height < 360) {
                $flags[] = 'low_resolution';
            }
            if ($width > 0 && $height > 0) {
                $ratio = max($width, $height) / max(1, min($width, $height));
                if ($ratio > 3.2) {
                    $flags[] = 'unusual_aspect_ratio';
                }
            }
        }

        $metadata = $this->readImageMetadata($file);
        foreach ($metadata as $value) {
            $text = Str::lower((string) $value);
            foreach (self::EDITOR_KEYWORDS as $keyword) {
                if (str_contains($text, $keyword)) {
                    $flags[] = 'edited_metadata_' . $keyword;
                }
            }
        }

        if (!$provider) {
            $flags[] = 'provider_not_detected';
        }

        $flags = array_values(array_unique($flags));
        $risk = empty($flags) ? 'low' : (count($flags) > 1 || $this->hasEditedFlag($flags) ? 'flagged' : 'review');

        if ($risk === 'flagged') {
            $confidence = min($confidence, 55);
        } elseif ($risk === 'review') {
            $confidence = min($confidence, 65);
        }

        return [
            'payment_proof_original_name' => $originalName,
            'payment_proof_provider' => $provider,
            'payment_proof_confidence' => $confidence,
            'payment_proof_risk' => $risk,
            'payment_proof_flags' => $flags,
            'payment_proof_analyzed_at' => now(),
        ];
    }

    private function readImageMetadata(UploadedFile $file): array
    {
        if (!function_exists('exif_read_data')) {
            return [];
        }

        $mime = $file->getMimeType();
        if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/tiff'], true)) {
            return [];
        }

        $exif = @exif_read_data($file->getRealPath());
        if (!is_array($exif)) {
            return [];
        }

        return array_intersect_key($exif, array_flip([
            'Software',
            'ProcessingSoftware',
            'Make',
            'Model',
            'Artist',
            'ImageDescription',
        ]));
    }

    private function hasEditedFlag(array $flags): bool
    {
        foreach ($flags as $flag) {
            if (str_starts_with($flag, 'edited_metadata_')) {
                return true;
            }
        }

        return false;
    }
}
