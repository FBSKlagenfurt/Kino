<?php
    //Hash password
	function HashPassword($password)
	{
	    $cost = 15;
	    $salt = sprintf("$2a$%02d$", $cost) . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	    return crypt($password, $salt);
	}
?>