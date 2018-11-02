<?php
// common_db.php - database connection using PHP::PDO ***
$dbloc = "mqs";// Use "local", "mq" or add others as appropriate
if ($dbloc == "mqs"){
        $dbhost = 'ash.science.mq.edu.au';
        $dbusername = '44616732';
        $dbuserpassword = 'QXJpYW5hIFJv';
        $default_dbname = '44616732';
}
else if ($dbloc == "local") {
	$dbhost = "127.0.0.1";
	$dbusername = "root";
	$dbuserpassword = "ab12345678!";
	$default_dbname = "comp344";
}

//Use PDO to connect to the database; return the PDO object
function db_connect() {
    global $dbloc, $dbhost, $dbusername, $dbuserpassword, $default_dbname, $oraDB, $sid;
    // Set a default exception handler, so that we don't spill our guts if a query fails.
    set_exception_handler("store_exception_handler");

    // Oracle Connection
    if ($dbloc == "mq") {
	    $db = new PDO("oci:dbname=".$oraDB, $dbusername, $dbuserpassword);
    }
    else {
    	// MySQL Connection
    	$db = new PDO("mysql:host=$dbhost;dbname=$default_dbname;charset=utf8", $dbusername, $dbuserpassword);
    }

    // $db = new mysqli($dbhost, $dbusername, $dbuserpassword, $default_dbname);
    $db->setAttribute(PDO::ATTR_PERSISTENT, true);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	return $db;
}

function store_exception_handler(RuntimeException $ex) {
	$debug = true;		// If true, report to screen; otherwise silently log and die.
	if(get_class($ex) == "PDOException") {
		if ($debug == true)
			echo "PDO Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ":<br/>Code " . $ex->getCode() . " - " . $ex->getMessage();
		 else
			error_log("PDO Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ": Code " . $ex->getCode() . " - " . $ex->getMessage());
	}
	else {
		error_log("Unhandled Exception in file " . basename($ex->getFile()) . ", line " . $ex->getLine() . ": Code " . $ex->getCode() . " - " . $ex->getMessage());
	// Any other unhandled exceptions will wind up at the store home page, for safety
	header("Location: index.php");
	}
}
?>
