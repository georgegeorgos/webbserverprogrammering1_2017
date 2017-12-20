<?php 
/**
* Felhantering för vanliga PHP-fel.
*
* Felen samlas i en array i stället för att visas direkt. 
* Arrayen kan skrivas ut som HTML.
*
* @author Lars Gunther O
* @version 0.01
*/ 
class handleErrors
{
	/** 
	* Error StaCk 
	* @var SerrorStack 
	*/
	 protected static $errorStack = array();

	 /**
	* Error names
	* @var error Names
	*/
	 Protected static $errorNames = array();
	/**
	* Första gången ska en del saker göras som inte behöver upprepas 
	* var $notFirstRun; 
	*/ 
	protected static $notFirstRun;
	/** 
	* Felhanteraren 
	* 
	* Denna metod fångar fel och lägger dem i arrayen $errorstack 
	*/ 
	public static function handler ($num, $str, $file, $line, $context) 
	{ 
		if ( empty (self::$notFirstRun) ) { 
			// Skapa en array som innehåller alla slags felkonstanter 
			// Stirra inte på koden här, det är vodoo ! 
			self::$notFirstRun = true; 
			$consts = get_defined_constants(true); 
			$consts = $consts['Core']; // Bara PHP:s grundkonstanter kvar 
			// Filtrera bort alla konstanter som inte börjar med "E_" 
			$filtered = array_flip(
				array_filter(array_keys($consts),'self::filter_error_consts'
				)
			);
			$filtered = array_intersect_key($consts,$filtered); 
			self::$errorNames = array_flip($filtered); 
			// Här slutar vodoo-koden
		}
		if ( error_reporting() == 0 ) { 
			// Fel hanteras inte om visning av fel stängts av 
			return;
		}
		// Lägg på felet i slutet av arrayen 
		self::$errorStack[] = array(
				$num, $str, basename($file), $line, $context 
		);
		return true;
	}
	/** 
	* Denna funktion returnerar felmeddelandena som HTML-kod. 
	*
	* En oil-lista skapas med ett felmeddelande per list item
	*
	* @param string $heading överskrift till felmeddelandena. 
	* @return string Formatterade felmeddellanden. 
	*/ 
	public static function messagesAsHTML( 
		$heading = "<h4> Felmeddelanden: <h4>\n" 
	) 
	{
		$message = $heading; 
		$message .= "<ol>\n" ;
		foreach ( self::$errorStack as $error ) {
			$type = self::$errorNames[$error[0]];
			$message .= <<<LI
			<li>
			<strong>{$type}</strong>
			på rad <em>{$error[3]}</em>
			i filen <em>{$error[2]}</em>
			Felmeddelande: {$error[1]} 
			</li>
LI;
		}
		$message .= "</ol>\n";
		return $message;
	}
	/**
	* Hjälpfunktion för att filtrera ut felkonstanter
	*
	* Denna används som callback till array_filter
	* @param string $constnamne Namn på konstanten 
	* @return bool 
	*/ 
	private static function filter_error_consts($constnamne)
	{
		// Notera att vi skiljer på 0 (noll) och false 
	return 0 === stripos ($constnamne, 'E_' );
	}
}
	
//---- slut på koden för felhantering ----------


//---- kod för Testkörning -------- 
header ("Content-type: text/html; charset=utf-8");

// Registrera funktion för felhantering 
set_error_handler('handleErrors::handler');

//---- skapa falska fel ---------
echo "<p>A<p>";
trigger_error("Fel ett", E_USER_WARNING);
echo "<p>B</p>"; 
trigger_error("Fel två", E_USER_ERROR); 
echo "<p>C</p>"; 
trigger_error ("Fel tre", E_USER_NOTICE);
	
//----- visas felmeddelanden ------------
echo handleErrors::messagesAsHTML();	 
