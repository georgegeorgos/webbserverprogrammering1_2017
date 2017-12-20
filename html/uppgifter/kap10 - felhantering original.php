<?php 

//---- kod för Testkörning -------- 
header ("Content-type: text/html; charset=utf-8");


//---- skapa falska fel ---------
echo "<p>A<p>";
trigger_error("Fel ett", E_USER_WARNING);
echo "<p>B</p>"; 
trigger_error("Fel två", E_USER_ERROR); 
echo "<p>C</p>"; 
trigger_error ("Fel tre", E_USER_NOTICE);
	 
