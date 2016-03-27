# Validator
Fast PHP Validator Class

    $valid = Validator::is_valid( $_POST, [
        'username' => 'required|min:3|max:30|alpha_digit',
        'password' => 'required|min:8|max:30',
    ]);

    if( ! $valid ){
        die( "OPS !?" );
    }
