<?php

namespace Helpers;

use yii\db\Query;

class KeyHelper
{
    const SEED = 'abcdefghijklmnopqrstuvwxyz'
    . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
    . '0123456789';

    const SEED_CAPS_ONLY = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
    . '0123456789';

    /**
     * @param string $table Which table the key should be generated for.
     * @param string $column Which column the key should be generated for.
     * @param int $length The length of the key.
     * @param bool $num_only Whether the key should only contain numbers
     * @param bool $caps_only Whether the key should only contain capitalized characters
     * @return string The unique key
     * @throws \Exception
     */
    public static function ByColumn($table, $column, $length = 32, $num_only = false, $caps_only = false)
    {
        while (true) {
            if ($num_only) {
                $min = pow(10, ($length - 1));
                $max = pow(10, $length) - 1;
                $return = random_int($min, $max);
            } else {
                $return = '';
                $seed = $caps_only ? static::SEED_CAPS_ONLY : static::SEED;
                $seedLength = strlen($seed);

                for ($i = 0; $i < $length; $i++) {
                    $return .= $seed[rand(0, $seedLength - 1)];
                }
            }

            $query = new Query();
            $result = $query->select($column)->from($table)->where([$column => $return])->all();

            if (!$result)
                return $return;

        }
        throw new \Exception('There are no unique possibilities left with these parameters.');
    }

    /**
     * Returns a random number (max length 19) based on the current timestamp.
     * @return mixed
     */
    public static function ByTime()
    {
        return sha1(random_int(0, 999999999) . time());
    }

    /**
     * Returns a random number based on the current timestamp with the current timestamp concatenated after an underscore.
     * @return string
     */
    public static function WithTime()
    {
        return static::ByTime() . '_' . time();
    }
}