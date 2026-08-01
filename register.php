<!DOCTYPE html>
<?php
session_start();

if (isset($_SESSION['username'])) { header("Location: index.php"); exit; }
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }
$Captcha = random_int(10000, 99999);
$_SESSION["Captcha"] = $Captcha;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fancy Interactive Form</title>
  <style>
    /* Reset & Base Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      # background: linear-gradient(135deg, #0f172a, #1e1b4b);
      background: linear-gradient(135deg, #1f2a0f, #2bcfc6);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    /* Container Card with Glassmorphism */
    .form-container {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 24px;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .form-container h2 {
      color: #fff;
      margin-bottom: 8px;
      font-size: 28px;
      font-weight: 700;
      text-align: center;
    }

    .form-container p {
      color: #94a3b8;
      font-size: 14px;
      text-align: center;
      margin-bottom: 32px;
    }

    /* Form Layout Grid */
    .fancy-form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    /* Interactive Floating Label Wrapper */
    .input-group {
      position: relative;
      width: 100%;
    }

    /* Input & Textarea Elements */
    .input-group input,
    .input-group textarea {
      width: 100%;
      padding: 16px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      outline: none;
      color: #fff;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .input-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    /* Custom Floating Label */
    .input-group label {
      position: absolute;
      left: 16px;
      top: 16px;
      color: #94a3b8;
      font-size: 16px;
      pointer-events: none;
      transition: all 0.3s ease;
    }

    /* Fancy Focus States & Value-Detection via Placeholder Trick */
    .input-group input:focus,
    .input-group textarea:focus {
      border-color: #6366f1;
      background: rgba(255, 255, 255, 0.08);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    /* Elevate label when input is focused OR has text inside */
    .input-group input:focus ~ label,
    .input-group input:not(:placeholder-shown) ~ label,
    .input-group textarea:focus ~ label,
    .input-group textarea:not(:placeholder-shown) ~ label {
      top: -10px;
      left: 12px;
      font-size: 12px;
      padding: 0 6px;
      color: #818cf8;
      background: #131536; /* Blends with gradient background */
      border-radius: 4px;
    }

    /* Accent Native Elements */
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
      accent-color: #6366f1;
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .checkbox-group label {
      color: #cbd5e1;
      font-size: 14px;
      cursor: pointer;
      user-select: none;
    }

    /* Premium Button Design */
    .submit-btn {
      background: linear-gradient(135deg, #6366f1, #4f46e5);
      color: #fff;
      border: none;
      padding: 16px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
      transition: all 0.2s ease;
    }

    .submit-btn:hover {
      background: linear-gradient(135deg, #4f46e5, #4338ca);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .submit-btn:active {
      transform: translateY(0);
    }
      
    /* Container holding both field groups */
    .form-row {
      display: flex;       /* Displays children horizontally */
      gap: 10px;           /* Adds space between the columns */
      margin-bottom: 10px; /* Adds spacing below the row */
    }

    /* Individual column wrappers */
    .form-group {
      flex: 1;             /* Makes both columns equal width */
      display: flex;
      flex-direction: column; /* Stack label above the input */
    }

    /* Styling the inputs to fit their containers */
    .form-group input {
      width: 100%;
      padding: 8px;
      box-sizing: border-box; /* Includes padding in width calculation */
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>mftracker Register</h2>
    <p>We would love to hear from you. Find the full code in github.</p>
    
    <form class="fancy-form" action="register_check.php" method="post">
      <!-- Name Field -->
      <div class="input-group">
        <input type="text" id="username" name="username" placeholder=" " minlength="8" required autocomplete="off">
        <label for="username">User ID (8 Char)</label>
      </div>

      <div class="input-group">
        <input type="text" id="fullname" name="fullname" placeholder=" " required autocomplete="off">
        <label for="fullname">User Full Name</label>
      </div>     
        
      <div class="input-group">
        <input type="text" id="email" name="email" placeholder=" " required autocomplete="off">
        <label for="email">Email ID</label>
      </div>           
        
      <!-- Email Field -->
      <div class="input-group">
        <input type="password" id="password" name="password" minlength="8" placeholder=" " required autocomplete="off">
        <label for="password">Password (8 Char)</label>
      </div>
        
      <!-- Native Accent Checkbox -->
      <div class="checkbox-group">
        <input type="checkbox" id="terms" required>
        <label for="terms">I agree to the privacy policy</label>
      </div>     
      
      <div class="form-row">
      <div class="input-group"> 
        <input type="text" id="Captcha" value="<? echo $Captcha;?>" name="Captcha" disabled>
        <label for="Captcha">Captcha</label>
      </div>
      <div class="input-group"> 
        <input type="text" id="Verify" name="Verify">
        <label for="Verify">Verify</label>
      </div></div>
      
      <!-- Submit Button -->
      <button type="submit" class="submit-btn" value="Login">Submit</button>
       
      <p>➜ Back to Login Page <a href="index.php">Click Here</a></p>
    </form>
  </div>

</body>
</html>
