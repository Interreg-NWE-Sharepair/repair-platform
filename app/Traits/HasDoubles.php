<?php

namespace App\Traits;

/**
 * Add this trait to a model to make doubles get and set correctly.
 * To add columns to act as doubles you should add them to: protected $doubles = ['column_name']; in your model.
 *
 * The config file is 'has-doubles.php'.
 */
trait HasDoubles
{
    /**
     * Indicates whether booleans are parsed to string or not.
     * You can change this value on the go by calling setParseDoubles($parseDoubles)
     *
     * @var bool
     */
    public $parseDoubles = true;

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($value && $this->isDoubleAttribute($key)) {
            $value = $this->fromDouble($value);
            $this->attributes[$key] = $value;

            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @param $key
     * @return string
     */
    public function getAttributeValue($key)
    {
        if (in_array($key, $this->getDoubles())) {
            $value = $this->getAttributeFromArray($key);

            if (!is_null($value)) {
                return number_format((float) $value, 2, config('has-doubles.separators.decimal.visual', ','), config('has-doubles.separators.thousand.visual', ''));
            }

            return $value;
        }

        return parent::getAttributeValue($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function isDoubleAttribute($key)
    {
        return in_array($key, $this->getDoubles(), true);
    }

    /**
     * @return mixed
     */
    public function getDoubles()
    {
        return $this->doubles;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function fromDouble($value)
    {
        return empty($value) ? $value : $this->asDouble($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function asDouble($value)
    {
        return str_replace(config('has-doubles.separators.decimal.on-save', config('has-doubles.separators.decimal.visual', ',')), config('has-doubles.separators.decimal.database', '.'), $value);
    }

    /**
     * @return bool
     */
    public function getParseDoubles(): bool
    {
        return $this->parseDoubles;
    }

    /**
     * @param bool $parseDoubles
     */
    public function setParseDoubles(bool $parseDoubles): void
    {
        $this->parseDoubles = $parseDoubles;
    }
}
