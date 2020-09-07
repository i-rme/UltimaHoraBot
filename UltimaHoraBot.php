<?php
require 'lib/SmithWatermanGotoh.php';
require 'lib/TwitterAPIExchange.php';

class UltimaHoraBot{

	private static $settings = array(
	    'oauth_access_token' => "[REDACTED]",
	    'oauth_access_token_secret' => "[REDACTED]",
	    'consumer_key' => "[REDACTED]",
	    'consumer_secret' => "[REDACTED]"
	);

	public static $triggerWords = array(
	    "ÚLTIMA HORA", "#ÚLTIMAHORA", "ÚltimaHora", "ULTIMA HORA", "#ULTIMAHORA", "UltimaHora"
	);

	public static $blacklistedWords = array(
		"#MinutoAMinuto"
	);

	private static $unwanted_array = array( 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
	'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
	'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
	'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
	'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );


	public static function similarity(string $string1, string $string2) {
		if($string1 == '' OR $string2 == '' ){
			return 0;
		}else{
			$smithWatermanGotoh = new SmithWatermanGotoh();
			$similarity = $smithWatermanGotoh->compare($string1, $string2);
		  	return $similarity;
		}
    }

	public static function write(array $array) {
		$data = serialize($array);
		$file = fopen(dirname(__FILE__).'/data/tweets.db', 'wb');
		fwrite($file, $data);

		return true;
	}
	
	public static function containsTLD(string $string) {
		preg_match(
			"/(AC($|\/)|\.AD($|\/)|\.AE($|\/)|\.AERO($|\/)|\.AF($|\/)|\.AG($|\/)|\.AI($|\/)|\.AL($|\/)|\.AM($|\/)|\.AN($|\/)|\.AO($|\/)|\.AQ($|\/)|\.AR($|\/)|\.ARPA($|\/)|\.AS($|\/)|\.ASIA($|\/)|\.AT($|\/)|\.AU($|\/)|\.AW($|\/)|\.AX($|\/)|\.AZ($|\/)|\.BA($|\/)|\.BB($|\/)|\.BD($|\/)|\.BE($|\/)|\.BF($|\/)|\.BG($|\/)|\.BH($|\/)|\.BI($|\/)|\.BIZ($|\/)|\.BJ($|\/)|\.BM($|\/)|\.BN($|\/)|\.BO($|\/)|\.BR($|\/)|\.BS($|\/)|\.BT($|\/)|\.BV($|\/)|\.BW($|\/)|\.BY($|\/)|\.BZ($|\/)|\.CA($|\/)|\.CAT($|\/)|\.CC($|\/)|\.CD($|\/)|\.CF($|\/)|\.CG($|\/)|\.CH($|\/)|\.CI($|\/)|\.CK($|\/)|\.CL($|\/)|\.CM($|\/)|\.CN($|\/)|\.CO($|\/)|\.COM($|\/)|\.COOP($|\/)|\.CR($|\/)|\.CU($|\/)|\.CV($|\/)|\.CX($|\/)|\.CY($|\/)|\.CZ($|\/)|\.DE($|\/)|\.DJ($|\/)|\.DK($|\/)|\.DM($|\/)|\.DO($|\/)|\.DZ($|\/)|\.EC($|\/)|\.EDU($|\/)|\.EE($|\/)|\.EG($|\/)|\.ER($|\/)|\.ES($|\/)|\.ET($|\/)|\.EU($|\/)|\.FI($|\/)|\.FJ($|\/)|\.FK($|\/)|\.FM($|\/)|\.FO($|\/)|\.FR($|\/)|\.GA($|\/)|\.GB($|\/)|\.GD($|\/)|\.GE($|\/)|\.GF($|\/)|\.GG($|\/)|\.GH($|\/)|\.GI($|\/)|\.GL($|\/)|\.GM($|\/)|\.GN($|\/)|\.GOV($|\/)|\.GP($|\/)|\.GQ($|\/)|\.GR($|\/)|\.GS($|\/)|\.GT($|\/)|\.GU($|\/)|\.GW($|\/)|\.GY($|\/)|\.HK($|\/)|\.HM($|\/)|\.HN($|\/)|\.HR($|\/)|\.HT($|\/)|\.HU($|\/)|\.ID($|\/)|\.IE($|\/)|\.IL($|\/)|\.IM($|\/)|\.IN($|\/)|\.INFO($|\/)|\.INT($|\/)|\.IO($|\/)|\.IQ($|\/)|\.IR($|\/)|\.IS($|\/)|\.IT($|\/)|\.JE($|\/)|\.JM($|\/)|\.JO($|\/)|\.JOBS($|\/)|\.JP($|\/)|\.KE($|\/)|\.KG($|\/)|\.KH($|\/)|\.KI($|\/)|\.KM($|\/)|\.KN($|\/)|\.KP($|\/)|\.KR($|\/)|\.KW($|\/)|\.KY($|\/)|\.KZ($|\/)|\.LA($|\/)|\.LB($|\/)|\.LC($|\/)|\.LI($|\/)|\.LK($|\/)|\.LR($|\/)|\.LS($|\/)|\.LT($|\/)|\.LU($|\/)|\.LV($|\/)|\.LY($|\/)|\.MA($|\/)|\.MC($|\/)|\.MD($|\/)|\.ME($|\/)|\.MG($|\/)|\.MH($|\/)|\.MIL($|\/)|\.MK($|\/)|\.ML($|\/)|\.MM($|\/)|\.MN($|\/)|\.MO($|\/)|\.MOBI($|\/)|\.MP($|\/)|\.MQ($|\/)|\.MR($|\/)|\.MS($|\/)|\.MT($|\/)|\.MU($|\/)|\.MUSEUM($|\/)|\.MV($|\/)|\.MW($|\/)|\.MX($|\/)|\.MY($|\/)|\.MZ($|\/)|\.NA($|\/)|\.NAME($|\/)|\.NC($|\/)|\.NE($|\/)|\.NET($|\/)|\.NF($|\/)|\.NG($|\/)|\.NI($|\/)|\.NL($|\/)|\.NO($|\/)|\.NP($|\/)|\.NR($|\/)|\.NU($|\/)|\.NZ($|\/)|\.OM($|\/)|\.ORG($|\/)|\.PA($|\/)|\.PE($|\/)|\.PF($|\/)|\.PG($|\/)|\.PH($|\/)|\.PK($|\/)|\.PL($|\/)|\.PM($|\/)|\.PN($|\/)|\.PR($|\/)|\.PRO($|\/)|\.PS($|\/)|\.PT($|\/)|\.PW($|\/)|\.PY($|\/)|\.QA($|\/)|\.RE($|\/)|\.RO($|\/)|\.RS($|\/)|\.RU($|\/)|\.RW($|\/)|\.SA($|\/)|\.SB($|\/)|\.SC($|\/)|\.SD($|\/)|\.SE($|\/)|\.SG($|\/)|\.SH($|\/)|\.SI($|\/)|\.SJ($|\/)|\.SK($|\/)|\.SL($|\/)|\.SM($|\/)|\.SN($|\/)|\.SO($|\/)|\.SR($|\/)|\.ST($|\/)|\.SU($|\/)|\.SV($|\/)|\.SY($|\/)|\.SZ($|\/)|\.TC($|\/)|\.TD($|\/)|\.TEL($|\/)|\.TF($|\/)|\.TG($|\/)|\.TH($|\/)|\.TJ($|\/)|\.TK($|\/)|\.TL($|\/)|\.TM($|\/)|\.TN($|\/)|\.TO($|\/)|\.TP($|\/)|\.TR($|\/)|\.TRAVEL($|\/)|\.TT($|\/)|\.TV($|\/)|\.TW($|\/)|\.TZ($|\/)|\.UA($|\/)|\.UG($|\/)|\.UK($|\/)|\.US($|\/)|\.UY($|\/)|\.UZ($|\/)|\.VA($|\/)|\.VC($|\/)|\.VE($|\/)|\.VG($|\/)|\.VI($|\/)|\.VN($|\/)|\.VU($|\/)|\.WF($|\/)|\.WS($|\/)|\.XN--0ZWM56D($|\/)|\.XN--11B5BS3A9AJ6G($|\/)|\.XN--80AKHBYKNJ4F($|\/)|\.XN--9T4B11YI5A($|\/)|\.XN--DEBA0AD($|\/)|\.XN--G6W251D($|\/)|\.XN--HGBK6AJ7F53BBA($|\/)|\.XN--HLCJ6AYA9ESC7A($|\/)|\.XN--JXALPDLP($|\/)|\.XN--KGBECHTV($|\/)|\.XN--ZCKZAH($|\/)|\.YE($|\/)|\.YT($|\/)|\.YU($|\/)|\.ZA($|\/)|\.ZM($|\/)|\.ZW)/i",
			$string,
			$M);
		  $has_tld = (count($M) > 0) ? true : false;
		  return $has_tld;
	}
	
	public static function cleanUrl(string $string) {
		$U = explode(' ',$string);

		$W =array();
		foreach ($U as $k => $u) {
		  if (stristr($u,".")) {
			if (self::containsTLD($u) === true) {
			unset($U[$k]);
			return self::cleanUrl( implode(' ',$U));
		  }
		  }
		}
		return implode(' ',$U);
	}
	
	public static function cleanTweet(string $tweetstr) {
		$tweetstr = self::cleanUrl($tweetstr);

		$tweetstr = mb_strtolower($tweetstr,"UTF-8");
		$replace = array_fill_keys(self::$triggerWords,'');
		$tweetstr = str_replace(array_keys($replace),$replace,$tweetstr);
	  
		$tweetstr = strtr( $tweetstr, self::$unwanted_array );
	  
		$tweetstr = str_replace(array("\r", "\n"), '', $tweetstr);
		$tweetstr = str_replace('| ', '', $tweetstr);
	  
		return $tweetstr;
    }

	public static function read() {
		$data = file_get_contents(dirname(__FILE__).'/data/tweets.db');

		if($data == FALSE){
                        self::write(array());
                }

		$new = array();

		$recent = unserialize($data);

		if (array_key_exists('created_at', $recent)) {
			$new_recent = array();
			$new_recent[0] = $recent;
			$recent = $new_recent;
		}

		foreach ($recent as &$recentTweet) {

			$tweet_time = new DateTime($recentTweet['created_at']);
			$hace_dos_horas = time() - (2 * 60 * 60);
			$fecha_hace_dos_horas = new DateTime( date('D M d H:i:s O Y', $hace_dos_horas) );

			if ( $tweet_time > $fecha_hace_dos_horas ){
				$new[] = $recentTweet; 
			}

		}

		return $new;
	}

	public static function contains(string $string, array $array) {
	    foreach($array as $a) {
	        if (stripos($string,$a) !== false) return true;
	    }
	    return false;
    }

	public static function getTweets() {
		$url = 'https://api.twitter.com/1.1/lists/statuses.json';
		$getfield = '?list_id=[REDACTED]&count=100&include_rts=false&include_entities=false';
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange(self::$settings);
		$resultado = $twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();

		$resultadoJSON = json_decode($resultado, true);

		// Chronologic order, newers go at the end
		$resultadoJSON = array_reverse($resultadoJSON);
		return $resultadoJSON;
	}

	public static function retweet(array $tweet) {

		$recent = self::read();

		if(count($recent) > 5){
			$recent = array_shift($recent);		//Removes one
		}

		$recent[] = $tweet;					//Adds current
		self::write($recent);
		self::doRetweet($tweet['id']);

		return true;
	}

	public static function doRetweet(string $tweet_id) {

		$url = 'https://api.twitter.com/1.1/statuses/retweet/'.$tweet_id.'.json';
		$requestMethod = 'POST';
		$postfields = array('id' => $tweet_id);
		$twitter = new TwitterAPIExchange(self::$settings);
		echo $twitter->buildOauth($url, $requestMethod)
				  ->setPostfields($postfields)
				  ->performRequest();

		return true;
	}
	
	public static function run() {
		$resultadoJSON = self::getTweets();

		foreach ($resultadoJSON as &$tweet) {
			self::checkTrigger($tweet);
		}	

		return true;
	}
	
	public static function checkTrigger(array $tweet) {

		if ( self::contains($tweet['text'], self::$triggerWords) AND !self::contains($tweet['text'], self::$blacklistedWords) ) {

			self::checkFreshness($tweet);

		}

		return true;
	}
	
	public static function checkFreshness(array $tweet) {

		$tweet_time = new DateTime($tweet['created_at']);

		$cinco_minutos = time() - (5 * 60);
		$fecha_cinco_minutos = new DateTime( date('D M d H:i:s O Y', $cinco_minutos) );

			if ( $tweet_time > $fecha_cinco_minutos ){
			
				self::checkRepetition($tweet);

			}

		return true;
	}
	
	public static function checkRepetition(array $tweet) {

		$recent = self::read();
		$maxSimilarity = 0.00;

		foreach ($recent as &$recentTweet) {
			
			$similarity = self::similarity(self::cleanTweet($tweet['text']), self::cleanTweet($recentTweet['text']));

			if($similarity > $maxSimilarity) {
				$maxSimilarity = $similarity;
			}

		}

		if($maxSimilarity < 0.16){
			self::retweet($tweet);
		}

    }

}	