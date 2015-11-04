<?php

/**
 * @author Donny van Walsem <donnehvw@gmail.com>
 */
class Validator
{

    /**
     * Contains any errors that come up while validating the input.
     * @var array $errors
     */
    private $errors = [];

    /**
     * The prefix that all validation functions must use, these
     * will be automatically called from the constructor.
     * @var const PREFIX
     */
    const PREFIX = "validate";


    public function __construct($forminput, $rules)
    {
        foreach($forminput as $name => $value) {
            $flags = explode('|', $rules[$name]);

            foreach ($flags as $flag) {
                $limit = '';
                if(strpos($flag, ':') !== false) {
                    $foo = explode(':', $flag);
                    $flag = $foo[0];
                    $limit = $foo[1];
                }
                call_user_func([$this, self::PREFIX . ucfirst($flag)], $name, $value, $limit);
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function validateRequired($name, $value)
    {
        if(empty($value)) {
            $this->errors[$name] = 'Het veld is niet ingevuld';
            return false;
        }

        return true;
    }

    private function validateString($name, $value)
    {
        if(!is_string($value)) {
            $this->errors[$name] = 'Het veld is geen string.';
            return false;
        }

        return true;
    }

    private function validateEmail($name, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$name] = 'Dit is geen geldig Email adres.';
            return false;
        }

        return true;
    }

    private function validateUnique()
    {
        //
    }

    private function validateMin($name, $value, $length)
    {
        if(count($value) < $length) {
            $this->errors[$name] = 'Veld is te kort.';
            return false;
        }

        return true;
    }

    private function validateMax($name, $value, $length)
    {
        if(count($value) > $length) {
            $this->errors[$name] = 'Veld is te lang.';
            return false;
        }

        return true;
    }

    /**
     * This function checks if the given value matches a specified regular expression.
     *
     * @param string $name
     * @param mixed $value
     * @param string $regexp
     * @return bool
     */
    private function validateRegexp($name, $value, $regexp)
    {
        if(!preg_match($regexp, $value)) {
            $this->errors[$name] = 'Fucking loser zo hoort het niet.';
            return false;
        }

        return true;
    }

    private function validateNointeger($name, $value, $length) {
        if (strcspn($value, '0123456789') != strlen($value)) {
            $this->errors[$name] = 'Veld bevat een getal.';
        }

        return true;
    }
}