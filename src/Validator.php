<?php

/**
 * ----------------------------------------------------
 * Validator
 * ----------------------------------------------------
 *
 * This class is able to filter the form input and see if
 * it matches given rules.
 *
 * @license MIT
 * @author Donny van Walsem <donnehvw@gmail.com>
 */


namespace Donneh;

class Validator
{

    private $data;
    private $rules;
    private $implicit_rules = [
        'required', 'email', 'ip', 'numeric',
        'integer', 'boolean', 'array', 'string',
        'json', 'url', 'date', 'file'
    ];
    private $errors;


    /**
     * The prefix that all validation functions must use, these
     * will be automatically called.
     * @var const PREFIX
     */
    const PREFIX = "validate";


    /**
     * Validator constructor.
     *
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->rules = $this->explodeRules($rules);
        $this->data = $data;
        $this->validate();
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function errors($key = null)
    {
        return empty($key) ? $this->errors : $this->errors[$key];
    }

    /**
     * Call all the validation methods.
     */
    private function validate()
    {
        foreach($this->rules as $key => $rule) {
            foreach($rule as $value) {
                $param = strpos($value, ':') !== false ? explode(':', $value) : null;
                $value = $param ? $param[0] : $value;



                $method = self::PREFIX . ucfirst($value);

                $this->$method($key, $this->data[$key], $param[1]);
            }
        }
    }

    /**
     * Build an array from the rules string by using explode on pipe.
     *
     * @param array $rules
     * @return array
     */
    private function explodeRules(array $rules)
    {
        foreach($rules as $key => $rule) {
            $rules[$key] = explode('|', $rule);
        }

        return $rules;
    }

    /**
     * Validate that the value is not empty.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateRequired($key, $value)
    {
        if(is_array($value) && empty($value)) {
            $this->errors[$key] = "{$key} can not be empty.";

            return false;
        } elseif(is_string($value) && !trim($value)) {
            $this->errors[$key] = "{$key} can not be empty.";

            return false;
        }

        return true;
    }

    /**
     * Validate if the value is a valid e-mail address.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateEmail($key, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$key] = "{$key} is not a valid e-mail address.";
            return false;
        }

        return true;
    }

    /**
     * Validate if the given value is a vaild IP address.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateIp($key, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_IP)) {
            $this->errors[$key] = "{$key} is not a valid IP address";
            return false;
        }

        return true;
    }

    /**
     * Validate if the given value is an integer.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateInteger($key, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[$key] = "{$key} is not an integer.";
            return false;
        }

        return true;
    }

    /**
     * Validate if the given value is numeric.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateNumeric($key, $value)
    {
        if(is_null($value) || !is_numeric($value)) {
            $this->errors[$key] = "{$key} is not an numeric value.";
            return false;
        }

        return true;
    }

    /**
     * Validate if the given value is a boolean.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateBoolean($key, $value)
    {
        $acceptable = [true, false, 1, 0, '1', '0'];

        if(!in_array($value, $acceptable, true)) {
            $this->errors[$key] = "{$key} is not a boolean";
            return false;
        }

        return true;
    }

    /**
     * Validate if given value is an array.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateArray($key, $value)
    {
        if(is_array($value)) {
            return true;
        }

        $this->errors[$key] = "{$key} is not an array";
        return false;
    }

    /**
     * Validate if given value is a string.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateString($key, $value)
    {
        if(is_string($value)) {

            return true;
        }

        $this->errors[$key] = "{$key} is not a string.";
        return false;
    }

    /**
     * Validate if the given value is valid JSON.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateJson($key, $value)
    {
        json_decode($value);

        if(json_last_error() === JSON_ERROR_NONE) {

            return true;
        }

        $this->errors[$key] = "{$key} is not valid json.";
        return false;
    }


    /**
     * Validate if the given value is a valid url.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateUrl($key, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$key] = "{$key} is not a valid url.";
            return false;
        }

        return true;
    }

    /**
     * Validate if the given value is a date.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function validateDate($key, $value)
    {
        if($value instanceof \DateTime) {
            return true;
        }

        $this->errors[$key] = "{$key} is not a valid date.";
        return false;
    }

    private function validateMax($key, $value, $length)
    {
        if(strlen($value) > $length) {
            $this->errors[$key] = "{$key} is longer than {$length} characters.";
            return false;
        }

        return true;
    }
}