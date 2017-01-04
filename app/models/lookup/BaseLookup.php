<?php

namespace app\models\lookup;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\base\Model;

/**
 * Description of BaseLookup
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class BaseLookup extends Model
{
    /**
     *
     * @var static[]
     */
    protected static $values = [];

    /**
     * @return string
     */
    public static function valueKey()
    {
        return 'value';
    }
    /**
     *
     * @return string
     */
    protected static function configFile()
    {
        $file = Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
        return __DIR__ . "/values/{$file}.php";
    }

    /**
     *
     * @return static[]
     */
    protected static function values()
    {
        $class = get_called_class();
        if (!isset(self::$values[$class])) {
            self::$values[$class] = [];
            foreach (require static::configFile() as $key => $value) {
                self::$values[$class][$key] = new static($value);
            }
        }
        return self::$values[$class];
    }

    /**
     *
     * @return static[]
     */
    public static function all()
    {
        return array_values(static::values());
    }

    /**
     *
     * @return static
     */
    public static function get($key)
    {
        $values = static::values();
        return isset($values[$key]) ? $values[$key] : null;
    }

    /**
     *
     * @return array
     */
    public static function range()
    {
        return array_keys(static::values());
    }

    /**
     * @return array
     */
    public static function enums()
    {
        $key = static::valueKey();
        $result = [];
        foreach (static::values() as $i => $value) {
            $result[$i] = $value->$key;
        }
        return $result;
    }
}
