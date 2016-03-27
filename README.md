# Validator
Fast PHP Validator Class

### How to
    $valid = Validator::is_valid( $_POST, [
        'username' => 'required|min:3|max:30|alpha_digit',
        'password' => 'required|min:8|max:30',
    ]);

    if( ! $valid ){
        die( "OPS !?" );
    }

### Available Validators

* required : Ensures the specified key value exists and is not empty
* min : Not shorter than the specified length $param
* max : Not longer than the specified length $param
* equal : Equal to the specified length $param
* alpha : Only aplha a->z, A->Z
* alpha_digit : Only alpha & digit a->z, A->Z, 0-9
* alpha_dash : Only alpha & digit & dash & underscore a->z, A->Z, 0-9, -, _
* alpha_space : Only alpha & digit & space a->z, A->Z, 0-9
* digit : Only digit 0-9
* integer : Only integer
* float : Only float
* bool : Only bool
* url : Valid Url
* url_exists : Check if url exists and is accessible
* email : Valid email
