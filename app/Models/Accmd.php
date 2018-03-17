<?php

namespace App\Models;
/**
 * App\Models\Accmd
 *
 * @property int $AC_KEY
 * @property string $AC_CODE
 * @property string $AC_NAME
 * @property string $AC_NAMELAT
 * @property INT $AC_AGEFROM
 * @property INT $AC_AGETO
 */
class Accmd extends BaseModel
{
    protected $table = 'Accmdmentype';
    protected $primaryKey = 'AC_KEY';
}
