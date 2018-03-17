<?php
namespace App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Laravelrus\LocalizedCarbon\Traits\LocalizedEloquentTrait;
/**
 * App\Models\BaseModel
 * @mixin \Eloquent  -инчае подсказки от Ide_helper-a не будут идти
 */

class BaseModel extends Model
{
//    use LocalizedEloquentTrait;
    const DATE_FORMAT = 'd.m.Y';
    const DEFAULT_CURRENCY = '$';
    const OK_STATUS = 7;
    const SITE_ID = 1; //fourthtour.com
    const DATE_ODBC_FORMAT = 'Y-m-d';

    /**
     * @var array
     */
    public static $sortable = [];

    public $perPage = 10;

    /**
     * Get sortables fields
     *
     * @return array
     */
    public function getSortable()
    {
        return static::$sortable;
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this, $options ?: JSON_UNESCAPED_UNICODE);
    }

    public function __toString()
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $value
     *
     * @return null|Carbon
     */
    public static function createDate($value)
    {
        if ( !$value ) return null;

        return Carbon::createFromFormat(self::DATE_FORMAT, $value);
    }

    /**
     * MSSQL  ные дела без этого работать не будет
     */
    public function getDateFormat()
    {
        //ФОрмат даты зависит больше от субд но в нашем случае это зависит от ОС
        if (CommonHelper::isDev()) {
            return 'Y-m-d H:i:s.u';
        }
        else{
            return 'Y-m-d H:i:s';
        }
    }

    public function fromDateTime($value)
    {
        if( strlen($value) >= 23 )
            return substr(parent::fromDateTime($value), 0, -3);
        else
            return $value;
    }

    public static function translit($ru_text){
        return transliterator_transliterate('Any-Latin; Latin-ASCII;', $ru_text);
    }

    /**
     * КОнвертим массив в UTF-8 сранный mssql до 2008 года не может хранить в utf-8
     * @param $array
     * @return mixed
     */
    public static function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    /**
     * Только потому что PDO работает по ебанутому с MSSQL в Wind и Nix
     * Создаёт строку запроса из заготовки и массива с параметрами , можно также передавать массив для IN
     * ВНИМАНИЕ! Не использовать ключи параметров являющихся числами с цифры к примеру :12 это приводит к конфликту с датой 2001-01-01 12:12:12
     * @param  string $psSql - пример SELECT * FROM table WHERE id = :ID AND some_field IN (:arrayParam)
     * @param  array  $phParams = [ 'ID' => 111, 'arrayParam' => [1, 'aaaaa', 3] ]
     * @return string
     */
    public static function query_prepare($psSql, $phParams = array())
    {
        preg_match_all('/\:(\w+)(\s*|\W+|$)/s', $psSql, $aMatches);

        /** @var array $aMatches */
        if (!empty($aMatches)) {
            $hCheck = array();
            arsort($aMatches[1]); //сортировка для того чтобы параметры с одинаковыми именами не накладывались друг на друга

            /** @var string|int|float|null $key */
            foreach ($aMatches[1] as $key) {
                if (isset($hCheck[$key]))
                    continue;

                //Если параметр число, то на фиг такой параметр это может быть время
                if( is_numeric($key) ) {
                    continue;
                }

                $hCheck[$key] = 1;

                if (isset($phParams[$key]) && is_string($phParams[$key]))
                    $phParams[$key] =  \DB::getPdo()->quote($phParams[$key])  ;

                if (!isset($phParams[$key]))
                    $phParams[$key] = 'NULL';

                //Поддержка массива для IN
                if (is_array($phParams[$key])) {
                    foreach ($phParams[$key] as &$value) {
                        if (is_string($value))
                            $value = \DB::getPdo()->quote($value);
                    }
                    $phParams[$key] = implode(',', $phParams[$key]);
                }

                $psSql = str_replace(":$key", $phParams[$key], $psSql);
            }
        }
        return $psSql;
    }

}
