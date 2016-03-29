<?php
/**
 * Fast PHP input validation class
 * @author http://github.com/cod3r3
 */

class Validator
{

    private static $_inputs = [];
    private static $_rules = [];
    private static $_errors = [];


    /**
     * Return all errors
     * @return array
     */
    public static function get_errors(){
        return self::$_errors;
    }


    /**
     * Return true if valid, else false
     * @param $inputs
     * @param $rules
     * @return bool
     */
    public static function is_valid( $inputs, $rules ){

        // check if inputs & $rules are an array's
        if( ! is_array( $inputs ) || ! is_array( $rules ) ){
            die('Validator#Error $inputs or $rules must be an array');
        }

        // check if arrays are empty
        if( ! isset( $inputs, $rules ) ){
            die('Validator#Error $inputs or $rules is empty');
        }

        // Init
        self::$_inputs = $inputs;
        self::$_rules = $rules;

        // Init errors, to erase the old errors from the memory
        self::$_errors = [];

        // lets start the testing
        self::check_rules();

        // Errors check
        if( count( self::get_errors() ) ){
            return false;
        }

        // Everything is cool
        return true;
    }


    /**
     * Loop throw each rule & call rule method to check
     */
    private static function check_rules(){

        // Each input key => rules
        foreach ( self::$_rules as $input_key => $rules_str ) {

            // maybe multiple rules
            if (strpos($rules_str, '|') !== false) {
                $rules = explode('|', $rules_str);
            }
            // just 1
            else {
                $rules = [$rules_str];
            }

            // Each Rule
            foreach ($rules as $rule) {

                // check for rule param
                if (strpos($rule, ':') !== false) {
                    $parts = explode(':', $rule);
                    $method = $parts[0];

                    self::$method($input_key, $parts[1]);
                }
                // No param
                else {
                    self::$rule($input_key);
                }

            }

        }

    }


    /**
     * Ensures the specified key value exists and is not empty
     * @param string $key
     */
    private static function required( $key ){
        if( ! ( isset( self::$_inputs[ $key ] ) && self::$_inputs[ $key ] !== '' ) ){
            self::$_errors[] = $key.":required";
        }
    }


    /**
     * Not shorter than the specified length $param
     * @param $key
     * @param $param
     */
    private static function min( $key, $param ){
        if( strlen( self::$_inputs[ $key ] ) < $param ){
            self::$_errors[] = $key.":min({$param})";
        }
    }


    /**
     * Not longer than the specified length $param
     * @param $key
     * @param $param
     */
    private static function max( $key, $param ){
        if( strlen( self::$_inputs[ $key ] ) > $param ){
            self::$_errors[] = $key.":max({$param})";
        }
    }


    /**
     * Equal to the specified length $param
     * @param $key
     * @param $param
     */
    private static function equal( $key, $param ){
        if( strlen( self::$_inputs[ $key ] ) != $param ){
            self::$_errors[] = $key.":equal({$param})";
        }
    }


    /**
     * Only aplha a->z, A->Z
     * @param $key
     */
    private static function alpha( $key ){
        if( ! preg_match( '/^[a-z]*$/i', self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":alpha";
        }
    }


    /**
     * Only alpha & digit a->z, A->Z, 0-9
     * @param $key
     */
    private static function alpha_digit( $key ){
        if( ! preg_match( '/^[a-z0-9]*$/i', self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":alpha_digit";
        }
    }


    /**
     * Only alpha & digit & dash & underscore a->z, A->Z, 0-9, -, _
     * @param $key
     */
    private static function alpha_dash( $key ){
        if( ! preg_match( '/^[a-z0-9\-\_]*$/i', self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":alpha_dash";
        }
    }


    /**
     * Only alpha & digit & space a->z, A->Z, 0-9,
     * @param $key
     */
    private static function alpha_space( $key ){
        if( ! preg_match( '/^[a-z0-9 ]*$/i', self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":alpha_space";
        }
    }


    /**
     * Only digit 0-9
     * @param $key
     */
    private static function digit( $key ){
        if( ! preg_match( '/^[0-9]*$/i', self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":digit";
        }
    }


    /**
     * Only integer
     * @param $key
     */
    private static function integer( $key ){
        if( ! is_integer( self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":integer";
        }
    }


    /**
     * Only float
     * @param $key
     */
    private static function float( $key ){
        if( ! is_float( self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":float";
        }
    }


    /**
     * Only bool
     * @param $key
     */
    private static function bool( $key ){
        if( ! is_bool( self::$_inputs[ $key ] ) ){
            self::$_errors[] = $key.":bool";
        }
    }


    /**
     * Valid url
     * @param $url
     */

    private static function url_filter( $url ){
        return filter_var( $url, FILTER_VALIDATE_URL);
    }

    private static function url( $key ){
        if( self::url_filter( self::$_inputs[ $key ] ) === false ){
            self::$_errors[] = $key.":url";
        }
    }

    private static function url_exists( $key ){

        if( self::url_filter( self::$_inputs[ $key ] ) ){

            $file_headers = @get_headers( self::$_inputs[ $key ] );
            
            if( $file_headers === false || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                self::$_errors[] = $key.":url_exists:url does not exists";
            }

        }
        else{
            self::$_errors[] = $key.":url_exists:not valid url";
        }

    }


    /**
     * Valid email
     * @param $key
     */
    private static function email( $key ){
        if( filter_var( self::$_inputs[ $key ], FILTER_VALIDATE_EMAIL) === false ){
            self::$_errors[] = $key.":email";
        }
    }

}
