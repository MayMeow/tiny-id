<?php

namespace Maymeow\TinyId;

class UuidShortener
{

    protected static string $base62Characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function encode(string $uuid, int $base = 62): string
    {
        $decimal = self::uuidToNumber($uuid);
        return self::baseEncode($decimal, $base);
    }

    public static function decode(string $encoded, int $base = 62): string
    {
        $decimal = self::baseDecode($encoded, $base);
        return self::numberToUid($decimal);
    }

    protected static function uuidToNumber(string $uuid): string
    {
        $hex = str_replace('-', '', $uuid);
        
        return gmp_strval(gmp_init($hex, 16), 10); 
    }

    protected static function numberToUid(string $number): string
    {
        $hex = gmp_strval(gmp_init($number, 10), 16);
        $hex = str_pad($hex, 32, '0', STR_PAD_LEFT);

        return sprintf('%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        );
    }

    protected static function baseEncode(string $number, int $base): string
    {
        $chars =  substr(self::$base62Characters, 0, $base);
        $str = '';
        $num = gmp_init($number, 10);

        while(gmp_cmp($num, 0) > 0) {
            $remainder = gmp_intval(gmp_mod($num, $base));
            $str = $chars[$remainder] . $str;
            $num = gmp_div_q($num, $base);
        }

        return $str === '' ? '0' : $str;
    }

    protected static function baseDecode(string $encoded, int $base): string
    {
        $chars = substr(self::$base62Characters, 0, $base);
        $chaMap = array_flip(str_split($chars));
        $num = gmp_init(0, 10);
        $length = strlen($encoded);

        for($i = 0; $i < $length; $i++) {
            $num = gmp_add(gmp_mul($num, $base), $chaMap[$encoded[$i]]);
        }

        return gmp_strval($num, 10);
    }
}