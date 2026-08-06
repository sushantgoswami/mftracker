<?php
session_start();

$username = $_SESSION['username'];

if (!isset($_SESSION['username'])) { header("Location: index.php"); exit; }
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>mftracker csv data upload</title>    
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	}	
	/* 1. Perfect Centering using Flexbox */
	body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
	}    
    .fancy-textarea-glow {
    width: 600px;
    max-width: 500px;
    height: 300px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background-color: #ffffff;
    color: #1e293b;
    font-family: 'Segoe UI', system-ui, sans-serif;
    font-size: 12px;
    line-height: 1.6;
    align: center;
    # resize: vertical; /* Only allow vertical resizing */
    outline: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    /* The magic fancy effect on click/focus */
    .fancy-textarea-glow:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15), 0 10px 15px -3px rgba(99, 102, 241, 0.05);
      background-color: #fafafa;
    }
    
    /* Beautiful placeholder styling */
    .fancy-textarea-glow::placeholder {
      color: #94a3b8;
      font-style: italic;
    }
	.submit-btn {
	    width: 100%;
	    padding: 14px;
	    background: linear-gradient(135deg, #667eea 0%, #ffa31a 100%);
	    border: none;
	    border-radius: 8px;
	    color: white;
	    font-size: 16px;
	    font-weight: 600;
	    cursor: pointer;
	    box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
	    transition: all 0.3s ease;
	}	
	.submit-btn:hover {
	    transform: translateY(-2px);
	    box-shadow: 0 6px 20px rgba(118, 75, 162, 0.4);
	}	
	.submit-btn:active {
	    transform: translateY(0);
	}
    </style>
</head>
<body>
<div>
<form name=inputform action="csvdata_check.php" method="POST">
  <h2>mftracker CSV data upload Form (user:<? echo $username; ?>)</h2><br>
                                
  <label for="feedback"><p>Paste your details as below format, select your date format correctly. </p></label><br><br>
  <textarea class="fancy-textarea-glow" id="csvdata" name="csvdata" rows="30" cols="80" placeholder="INF174KA1EN7,KOTAK_FOCUSED_FUND_DIRECT,22/09/2023,19.766,50.589"></textarea>
  <br>
  <p>Select your data Date format:</p>

  <input type="radio" id="date" name="date" value="date0" checked>
  <label for="date0">Date Style default database (yyyy/mm/dd)</label><br>    
  <input type="radio" id="date" name="date" value="date1">
  <label for="date1">Date Style (dd/mm/yyyy)</label><br>
  <input type="radio" id="date" name="date" value="date2">
  <label for="date2">Date Style (mm/dd/yyyy)</label><br>
  <input type="radio" id="date" name="date" value="date3">
  <label for="date3">Date Style (dd/mm/yy)</label><br>
  <input type="radio" id="date" name="date" value="date4">
  <label for="date4">Date Style (mm/dd/yy)</label><br>
  <br>                             
  <button class="submit-btn" type="submit">Submit</button>   
</form>
</div>
</body>
</html>
