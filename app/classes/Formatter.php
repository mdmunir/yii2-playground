<?php

namespace app\classes;

/**
 * Description of Formatter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Formatter extends \yii\i18n\Formatter
{
    const ALFANUM_START = 792879840; // 1027572272640;

    public function asDbDate($value)
    {
        return $this->asDate($value, 'yyyy-MM-dd');
    }

    public function asAlfanum($value, $case = 'upper')
    {
        $result = [];
        $A = $case == 'upper' ? ord('A') : ord('a');
        while ($value > 0) {
            $x = $value % 36;
            $result[] = $x < 10 ? $x : chr($x - 10 + $A);
            $value = floor($value / 36);
        }
        return implode('', array_reverse($result));
    }

    public function asAlfanumCs($value, $extra = ['-', '_'])
    {
        $result = [];
        $A = ord('A');
        $a = ord('a');
        while ($value > 0) {
            $x = $value % 64;
            if ($x < 10) {
                $result[] = $x;
            } elseif ($x < 36) {
                $result[] = chr($x - 10 + $A);
            } elseif ($x < 62) {
                $result[] = chr($x - 36 + $a);
            } else {
                $result[] = $extra[$x - 62];
            }
            $value = floor($value / 64);
        }
        return implode('', array_reverse($result));
    }
}
