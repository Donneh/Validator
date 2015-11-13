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


    /**
     * Validator constructor.
     * @param array $forminput
     * @param array $rules
     */
    public function __construct(array $forminput, array $rules)
    {
        array_walk( array_keys( $rules ), function( $expected ) {
            if( !array_key_exists( $expected, $forminput ) || empty( $forminput[ $expected ] ) ) {
                Throw new RuntimeException( 'Expected input value is either empty or doesn\'t exist.' );
            }
        });

        foreach($forminput as $name => $value) {
            $flags = explode('|', $rules[$name]);

            foreach ($flags as $flag) {
                $limit = '';
                if(strpos($flag, ':') !== false) {
                    $scraps = explode(':', $flag);
                    $flag = $scraps[0];
                    $limit = $scraps[1];
                }
                call_user_func([$this, self::PREFIX . ucfirst($flag)], $name, $value, $limit);
            }
        }
    }

    /**
     * Returns all errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Checks if the field is not null.
     *
     * @param string $name
     * @param string $value
     * @return bool
     */
    private function validateRequired($name, $value)
    {
        if(empty($value)) {
            $this->errors[$name] = 'Het veld is niet ingevuld';
            return false;
        }

        return true;
    }

    /**
     * This function validates the value and if it's a string
     * it will return true.
     *
     * @param string $name
     * @param string $value
     * @return bool
     */
    private function validateString($name, $value)
    {
        if(!is_string($value)) {
            $this->errors[$name] = 'Het veld is geen string.';
            return false;
        }

        return true;
    }

    /**
     * Validates if the given value is a valid email address.
     *
     * @param string $name
     * @param mixed $value
     * @return bool
     */
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

    /**
     * See if the given value is longer than the given length.
     *
     * @param $name
     * @param $value
     * @param $length
     * @return bool
     */
    private function validateMin($name, $value, $length)
    {
        if(count($value) < $length) {
            $this->errors[$name] = 'Veld is te kort.';
            return false;
        }

        return true;
    }

    /**
     * Check if the given string is longer than the specified length.
     *
     * @param $name
     * @param $value
     * @param $length
     * @return bool
     */
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

    /**
     * Check if the given value is an integer.
     *
     * @param $name
     * @param $value
     * @return bool
     */
    private function validateInteger($name, $value)
    {
        if(preg_match('/[^0-9]+$/', $value)) {
            return true;
        }

        $this->errors[$name] = "Dit is geen getal.";

        return false;
    }
}