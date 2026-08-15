<?php
    
if (isset($_GET['id'])) {
    $id = $_GET['id']; }
    // echo "The ID is: " . $id;
    
// Define a strong, secret 32-byte encryption key (Keep this strictly confidential)
// $encryption_key = "your-super-secret-32-byte-key-here!!"; 
$encryption_key = $id;
    
// The raw PHP code you want to turn into an executable token
// $php_code_to_execute = 'echo "Hello! This code was securely decrypted and executed.";';

// AES-256-CBC requires a 16-byte Initialization Vector (IV)
$iv_length = openssl_cipher_iv_length('AES-256-CBC');
$iv = random_bytes($iv_length); // Generates a cryptographically secure random IV

// Encrypt the PHP code
$encrypted_code = openssl_encrypt($php_code_to_execute, 'AES-256-CBC', $encryption_key, 0, $iv);

// Pack the IV and encrypted text together, then base64 encode them for safe transport/storage
$token = base64_encode($iv . '::' . $encrypted_code);

echo "Encryption key:\n" . $encryption_key;
echo " ";
echo "Generated Executable Token:\n" . $token;
?>