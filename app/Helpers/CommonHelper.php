<?php
namespace App\Helpers;

class CommonHelper
{
    /**
     *
     * ----------------------------------------------
     * isDev() - Check if we are in a Dev Env.
     * ----------------------------------------------
     * @return bool
     *
     */
    public static function isDev(){
        if (isset($_GET['env']))
            return false;
        return (env('APP_ENV') == 'dev');
    }

    public static function isProd(){
        return (env('APP_ENV') != 'dev');
    }

    /**
     * КОнвертим во что угодно
     * @param mixed $array - string or array
     * @param string $encode_to
     * @param string $from_encoding
     * @return mixed
     */
    public static function encode_to($array, $encode_to = 'utf-8', $from_encoding = 'utf-8')
    {
        if ($encode_to == $from_encoding)
            $from_encoding = null;
        if (is_string($array) && self::get_encoding($array) != $encode_to){
            return mb_convert_encoding($array, $encode_to, $from_encoding);
        }
        else if( is_array($array) ) {
            array_walk_recursive($array, function (&$item, $key) use ($encode_to, $from_encoding) {
                //if(!mb_detect_encoding($item, $encode_to, true)){
                if (self::get_encoding($item) != $encode_to) {
//                    print "$key => $item NOT $encode_to<br>";
                    $item = mb_convert_encoding($item, $encode_to, $from_encoding);
                } else {
//                    print "$key => $item IS $encode_to<br>";
                }
            });
        }

        return $array;
    }

    static function get_encoding($str){
        $cp_list = array('utf-8', 'cp1251');
        foreach ($cp_list as $k=>$codepage){
            //print "$codepage, $codepage  ".iconv($codepage, $codepage, $str)."<br>";
            // @iconv - потому что переодически выкидывает iconv(): Detected an incomplete multibyte character in input string
            if (md5($str) === md5(@iconv($codepage, $codepage, $str))) {
                return $codepage;
            }
        }
        return null;
    }

    /** Получаем IP адрес */
    public static function get_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
