<?php
function generateHash($password) {
	if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
		$salt = '$2a$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
		return crypt($password, $salt);
	}
}

print("plain password: " . $argv[1] . PHP_EOL);
print("encrypted password: " . generateHash($argv[1]) . PHP_EOL);
