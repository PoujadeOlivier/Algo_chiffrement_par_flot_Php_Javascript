<?php

//DEBUG PHP
function ChaineToBinaire($str){
    $str_len = strlen($str);
 
    for($i = 0 ; $i < $str_len ; $i++){
        echo sprintf("%'.08d\n", base_convert(ord($str), 10, 2));
        $str = substr($str, 1);
    }
}

//TUNNELLING : chiffrement Rivest Cipher 4 PHP Side
//http://web.archive.org/web/20060810225251/http://farhadi.ir/rc4.html
/* RC4 symmetric cipher encryption/decryption
 * Copyright (c) 2006 by Ali Farhadi.
 * released under the terms of the Gnu Public License.
 * see the GPL for details.
 *
 * Email: ali[at]farhadi[dot]ir
 * Website: http://farhadi.ir/
 */

/**
 * Encrypt given plain text using the key with RC4 algorithm.
 * All parameters and return value are in binary format.
 *
 * @param string key - secret key for encryption
 * @param string pt - plain text to be encrypted
 * @return string
 */
function TunnelRC4_PHP($key, $pt, $base64) {
	
	
	//olivier : modification au code original
	// on se met en ASCII
	if($base64 == 'base64_decode')
	{
		$pt = base64_decode($pt);
	}
	else
	{
		$pt = base64_encode($pt);
	}		
	//fin modif	
	
	
	//olivier :
	//On initialise un tableau de 255 valeurs
	$s = array();
	for ($i=0; $i<256; $i++) {
		$s[$i] = $i;
	}
	
	//print_r($s);exit;

	
	//olivier :
	//on mélange les 255 valeurs consécutives de manière unique en fonction de la clé secrète en entrée
	$j = 0;
	$x;
	for ($i=0; $i<256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;		
	}
	
	
	/*
	echo"<br/>";
	echo"<br/>";
	print_r($s);		
	echo"<br/>";		
	exit;	
	*/
	
	
	
	
	
	$i = 0;
	$j = 0;
	$ct = '';
	$y;

	
    // Discard the first 2304 bytes of the keystream to mitigate the
    // Fluhrer/Mantin/Shamir attack	



//RC4 ndrop
/*
	//olivier : ajouter au code original
	$len = strlen($pt) + 2304;
	//fin modif	
	
	//olivier : modification au code original	
	//for ($y=0; $y<strlen($pt); $y++) {
	for ($y=0; $y<$len; $y++) {
	//fin modif
		$i = ($i + 1) % 256;

		$j = ($j + $s[$i]) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		
		//olivier : modification au code original
		//$ct .= $pt[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		
		//On ne tient pas compte des 2303 premières itérations trop prédictives !! (ndop)
		if ($y > 2303) {
			$ct .= $pt[$y - 2304] ^ chr($s[($s[$i] + $s[$j]) % 256]);
			
			echo ChaineToBinaire(chr($s[($s[$i] + $s[$j]) % 256]));
			echo" ";
			
		}
		//fin modif		
	}
*/





/*
	for ($y=0; $y<strlen($pt); $y++) {

		$i = ($i + 1) % 256;

		$j = ($j + $s[$i]) % 256;
		
		
		//echo " $i ($j + $s[$i]) % 256 = ".$j;
		//echo"<br/>";		
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		
		//^ XOR  chr Générer une chaîne d'un octet à partir d'un nombre
	
*/
	
	
	
	
//SPRITZ ndrop

    //Pseudo-random generation algorithm (PRGA)
	$len = strlen($pt) + 12789;
    $w = 133;
    //for ($z = 0, $k = 0, $i = 0, $j = 0, $res = '', $y = 0; $y < strlen($pt); $y++)
    for ($z = 0, $k = 0, $i = 0, $j = 0, $res = '', $y = 0; $y < $len; $y++)
    {
        $i = ($i + $w) % 256;
        $j = ($k + $s[($j + $s[$i]) % 256]) % 256;
        $k = ($k + $i + $s[$j]) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $z = $s[($j + $s[($i + $s[($z + $k) % 256]) % 256]) % 256];
		//chr génère une chaîne d'un octet à partir d'un nombre, d'où le modulo 256 partout
        //$ct .= $pt[$y] ^ chr($z);
		if ($y > 12788) {
			$ct .= $pt[$y - 12789] ^ chr($z);
			
			//echo ChaineToBinaire(chr($s[($s[$i] + $s[$j]) % 256]));
			//echo" ";
			
		}		
    }
	
	
	
	//olivier : modification au code original
	if($base64 == 'base64_encode')
	{
		$ct = base64_encode($ct);
	}
	else
	{
		$ct = base64_decode($ct);
	}		
	//fin modif	
	
	
	return $ct;
}




$message = "Cette page est un exemple de texte utilisable pour débuter avec les versions 1.x de Cryptool (CT1)

CrypTool 1 (CT1) est un programme complet et gratuit d'apprentissage de la cryptographie et de la cryptanalyse, offrant une vaste Aide en ligne et plusieurs démonstrations visuelles.

Ce fichier texte est destiné à vous aider dans vos premiers pas avec CT1.

1) Pour débuter nous vous recommandons de lire l'Aide en ligne (pas encore traduite en Français). Elle vous donnera une vue d'ensemble des fonctions disponibles dans cette application.
La page initiale de l'Aide en ligne peut être affichée via le menu 'Aide -> Page d'accueil' en haut à droite de l'écran, ou par la recherche de 'Starting page' dans l'index de l'Aide.

Vous pouvez lancer l'Aide en ligne à tout moment dans CT1, avec la touche F1.

2) Une seconde étape peut être de chiffrer un texte par la méthode de César. Ceci peut être fait via le menu 'Chiffrer/Déchiffrer -> Symétrique (classique)'.

3) Il y a plusieurs exemples (tutoriels) dans l'Aide en ligne, qui permettent une approche facile de la cryptologie. Ces exemples peuvent être obtenus par le menu 'Aide -> Scénarios (Tutoriels)'.

4) Vous pouvez aussi étendre vos connaissances par:
- la navigation dans les menus. Vous pouvez faire F1 à tout niveau dans les menus, si vous désirez plus d'informations.
- la lecture du fichier Lisez-moi -pas encore disponible en Français- (voir le menu 'Aide -> Lisez-moi').
- l'affichage de la présentation en couleurs. Cette présentation peut être affichée de plusieurs façons: par exemple dans le menu 'Aide', ou via la section 'Documentation' disponible dans la page d'accueil de l'Aide.
- la consultation du site web www.cryptool.org.

Fèvrier 2018
L'équipe CrypTool";


$motdepasse = 'secrgdrtretetdjqosifjpojopijoij';

echo "<br/>";

echo TunnelRC4_PHP($motdepasse, $message, 'base64_encode');
echo "<br/>";
echo TunnelRC4_PHP($motdepasse,TunnelRC4_PHP($motdepasse, $message, 'base64_encode'),'base64_decode');
echo "<br/>";
echo "FIN";


/*
Reverse javascript
TunnelRC4_JS('secrgdrtretetdjqosifjpojopijoij','bPDJXsOpwbzsWSL2R6y6hlbq6K9yoleXuok534BhHxP6SpDJAyfjbTSGlxwjyaEsIOprOt39joRAWvZ8REJabCphSbaWxAAAn+zrjWDCMfQBD+SPUtl+BR3Fzkc3rHDS3W642QXxMf6AWXx8/4O81x60wmcWSHRnf6dIje4JaOhPPWkRY7mj/zEkRmpStl8fDlkUAWIpQLUHXSWLGkilMQumuTUJG1S56YR9VyncyOfkLleWF/2ucGOzxLgNjFsaKa2QZ1aytuobJtFqKAAIKnDVz5LgD52z2lxoOMmcfEu8Os4YcsrfpitpgYj82gCX6JTldl7SbHM2yPAamSjNQ/xtkW4+cJV2iiDB7J2ux8wKbcM+ZD4csMLVJV4Lzubtr4yXOr/pEFRHfhLp7X7Te0kaTW91HZzzFGSmGHHFCA134wcV/ozAYr/Q+py+M8Vl/PXnLnmzNHAepItlVOqlgwpEmnX+3WML55ieBW9LtlZVPgC1EZ0eHQSAYuxK0snBVSfH7PF6TeG+wAaXwsWjhNOYFaUbjxMsfE152TJWyR0T8s+2pHBHzuD/Ympada0NbmHNC7UwYUjhmMC/1papgRB7EzbjoCr4mwPqoXjbxZGPANWZ+M6s6EpGQfIYnG0BNoc7EiUD54mz67FDMIzlxze0LL6BzKqa3DwBknYn7yln7dtecHQKBO/BaxZSGjaVFJ4062/NpZLJaq+GN2Lo/aDx/ybIzOA9HGurkr+q7R6yVDUHCrDPOIKRNffz51daFNuCtSTTMiSB3w7/K8p+T6bNg7iTTZL5PqxuzYEw3OC2CSm/OKXNTd0JDy2YlynWIZOV6tIxVXYlyTacy1wJudw9WMaIhF7/OEa+iVpaIWSfMroEZ8b5Bm4QjuPiR/Wf7MA/kntSuTeQYVFTeyVSZPAZhgGEiIs6iXdG5JmxLyAq1kcIapTf64C6TyCVGfVqIfNlxiuo6VD8eTCF1fmKBceVsu1JoXfZIIwULUvRctofDutzJtkBfLeTqnImD03VSRkBtRPAnWkG1rPdyA2n2QuUnjamzLQArGD1o99bLC4FKO3i/TpsizrQhdN+ALl79AbKXUimdAKaG6tvY21FoN8QCnlgXIeXxVrPrK3sINNLSF6pa3kBV40hKxMGia8kdMI2VX/dk8/1AoRPRrAChB//Grfv09qbP3QEljandvXoFCC68AQR6uMhFhRMMRe16xlCNq35gJ+zlLZPY4XVrMbtk1P89TTLKDUjK/4onZrKahSvGz7jMYIGY2hJl4XUOTnyJOTR5IRvNxntOmTyc3Mg/q9Si2zqRn2zMW0Hr2O6oZNhhA04NmQUzwx/+xM58A28CY73GxCp90hvSRGal2mOhjRUuM547L4J5JkUk2NUwv51GXGVVB9GSq9FeH4EjPNY7xwDHY4kJLW6Rk1y+O8po+E1vOxDct2PnClhgWKqGCbsemi63H1yqMvn5C1rp6k7leQlLN7XaWsjlLkYLEWUguj3VT6RrDyPsfN31Ao1ZhQGgmqpxExHAz41wLaxM+YkXYCHXR02mib0BQFfouIKoMz7ObbPjjA6kgXyl5WzIyflV9Y/h0gnuerFfDv0rIMoujOi9GA2ce86Sv6pjP9hCjeqLdy25k8A3X+WfCfsVA08enF8RMV1zOaDkjmB2adspgCHZsgBT11YxsZr505D8pEQnmBhNO/maGUHzZSLnnwM8tCkApuNWT2uRuP3JaMP68TaNNGYR2i0c+cs4aFQFe/sGzgghUA2EqD8+JXhrGLQZgXQjC7JZ2ZIbGor/YfZv2ygci8g35aK4sa08b7MW1lzmatkJj8qJOik9rMDPmlSUYoif9xF2COuIC7IwRC5wjCM37+Y6xQ/0UR0CcQuaRPhBWTRb/2j+DaXRta74+XIEG4Rtf5gH2xmGQjr86RzjbkddV/mT+oUdorAW3ZaKrMiQNgaT7+wuNDkmOfyGQoOIjnedzuLVdCyDarojfg2Sc+OfjRi67q7fsoEDP4SPR7/+jOmVRZX3L+yiHIQUKfFgEygdudZENIjJtqk6/c4tV4GmQMLvUV21bByEdjFlHX2kwFKqC8bQhFLo7iA1KTbuY+KSmN3uGpbAj+WPaLPdpe3Hc27pgxGNv/g1eHMkUdoq+H76Gwnd6IpzAeLgOIeT8hCpYaiEz3zLskaSf+tTyjWNu/gAr6gnFHT1wujgwbfHdDW4hz5XtmTEzTC9LcDXt8Rq5V4qMRALWOUtW/R69xF6uWdm3xFVV68DqILNQ88XDFkoIzjuSOOsqrjocOLXHFKSw2RKcMiQY52NAuRCIvdlkxG7N7JceGS8ZNj1rynrrF8BAtwC66bY5NOReXa2Xe0J007rU4vuHLHqip3OQ9Zzs2FvSw6uWORfS3M3Sz3D33T9bX9BjVFPoP1wGrb4YIAHDixsfa/nkfCEf0PZUoPcBL3koqSeMvZrGxoE/oUIzfCWgkdYsSvHAKOIracqqEXCDDMjffD8QXwY9psQKRZicmpOTMLL3IpGgZ25raLWJhY2YmtpcQ6zN11waQUmFx9014MisJDcPcX85u4qamh8PUouIKGwJaCuSjdbDGJcWWjJO5epTARZmFvcXeOIBi9EnfwQkQxDwTiiXNoL3R0hbjpSnzkJ+q496H4fON77WV1aHbd/rzXij7Y5UrgaPQK9TjYEbDqod08N+SnyfzUsj94wv562Gf8CJwK/Sa6UhNMtNiTCDBEpE49yTQSpiu8BCcOk7Mncx1KAcygLkyKmhzYw9qDK/i6gcOcJ6B1xL/MVrBYmAEdku/Aiv9HzhnlfrmQeSMWd67jPQHQPnOyvZgqqUNEFmL6rTm252lkIxdJnpoiF9vbpw7ruGtc9CMy/FsNQn6wjO263LAoz6Wf4HVN3uO9r/XIK17bmEtAc9xhV+00fGGFJfBPteKokrLBkuoZClrkY/DZ1FAH1DIRWwBzrFsiU33mAEvQv3LpketWm11DQZbPF5Gc5BL62bPaHjeVa98W1APR6THcxoZSbqoA3jB9GSIfHOACXuVq5YbX8kZTwHmfQDBXGSBGf6HPss8UMWZpQ3HUNhdK2set2UHuIK6q2n7A9hkWntTLUOGt4uIZ8R5jp3MeOKI4OMLVzKlBxYkGR0yq6R+syTo7GHXO+DqpwI8vnsZ0kgAUWY3HmfKp64nSsTqVNw+m','atob')
*/


//Olivier
//je fais un base64_encode et base64_decode sur le résultat final pour la transmission sur le réseau



/*
Si on utilise 2 fois la même clé, 
ça peut-être gênant,
si on a 2 textes chiffrés et 1 déchiffré , on peut déchiffrer le second!!

http://xor.pw
https://fr.wikipedia.org/wiki/Masque_jetable

+ => XOR

c1 = m1 + key
c2 = m2 + key
c1 + c2 = (m1+k) + (m2+k) = (m1+m2) + (k+k) = m1 + m2
c1 + c2 + m1 = m2


KK: 1010
M1: 1111
C1:  101


KK: 1010
M2: 1100
C2:  110




C1: 1111
C2: 1100
      11

M1: 101
M2: 110
     11




C1:  101
C2:  110
->    11 
M1: 1111
11

C1:  101
C2:  110
->    11 
M2: 1100
    1111
*/

function spritz($str, $key, $base64)
{
	//olivier : modification au code original
	// on se met en ASCII
	if($base64 == 'base64_decode')
	{
		$str = base64_decode($str);
	}
	else
	{
		$str = base64_encode($str);
	}		
	//fin modif	
	
    //Key-scheduling algorithm (KSA)
    for ($s = array(), $i = 0; $i < 256; $i++)
        $s[$i] = $i;
 
    for ($j = 0, $i = 0; $i < 256; $i++)
    {
        $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
    }
    //Pseudo-random generation algorithm (PRGA)
    $w = 1;
    for ($z = 0, $k = 0, $i = 0, $j = 0, $res = '', $y = 0; $y < strlen($str); $y++)
    {
        $i = ($i + $w) % 256;
        $j = ($k + $s[($j + $s[$i]) % 256]) % 256;
        $k = ($k + $i + $s[$j]) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $z = $s[($j + $s[($i + $s[($z + $k) % 256]) % 256]) % 256];
        $res .= $str[$y] ^ chr($z);
    }
	
	//olivier : modification au code original
	if($base64 == 'base64_encode')
	{
		$res = base64_encode($res);
	}
	else
	{
		$res = base64_decode($res);
	}		
	//fin modif		
	
    return $res;
}










//VPMC

function vmpc($str, $key, $base64)
{
	
	if($base64 == 'base64_decode')
	{
		$str = base64_decode($str);
	}
	else
	{
		$str = base64_encode($str);
	}
	
    //Key-scheduling algorithm (KSA)
    for ($s = array(), $i = 0; $i < 256; $i++)
	{	
        $s[$i] = $i;
    }
	
	for ($k = 0; $k < 3; $k++)
	{
        for ($j = 0, $i = 0; $i < 256; $i++)
        {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
    }
	
	//Pseudo-random generation algorithm (PRGA)
    for ($i = 0, $j = 0, $x = 0, $res = '', $y = 0; $y < strlen($str); $y++)
    {
        $x = $s[$i];
        $j = $s[($j + $x) % 256];
        $res .= $str[$y] ^ chr($s[$s[($s[$j] + 1) % 256]]);
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $i = ($i + 1) % 256;
    }

	if($base64 == 'base64_encode')
	{
		$res = base64_encode($res);
	}
	else
	{
		$res = base64_decode($res);
	}		
	
    return $res;
}
 
function vmpcv2($str, $key, $base64)
{
	
	if($base64 == 'base64_decode')
	{
		$str = base64_decode($str);
	}
	else
	{
		$str = base64_encode($str);
	}
	
    //Key-scheduling algorithm (KSA)
    for ($s = array(), $i = 0; $i < 256; $i++)
        $s[$i] = $i;
    for ($k = 0; $k < 3; $k++)
        for ($j = 0, $i = 0; $i < 256; $i++)
        {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
    //Pseudo-random generation algorithm (PRGA)

	$len = strlen($str) + 4096;

   // for ($i = 0, $j = 0, $x = 0, $res = '', $y = 0; $y < strlen($str); $y++)
    for ($i = 0, $j = 0, $x = 0, $res = '', $y = 0; $y < $len; $y++)
    {
        $x = $s[$i];
        $j = $s[($j + $x) % 256];
		//v1
//      $res .= $str[$y] ^ chr($s[$s[($s[$j] + 1) % 256]]);
		//v2
        //$res .= $str[$y] ^ chr($s[($s[$s[$j]] + 1) % 256]);
 		if ($y > 4095) {      
		  $res .= $str[$y - 4096] ^ chr($s[($s[$s[$j]] + 1) % 256]);
		}
		
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $i = ($i + 1) % 256;
    }
	
	if($base64 == 'base64_encode')
	{
		$res = base64_encode($res);
	}
	else
	{
		$res = base64_decode($res);
	}		
	
    return $res;
}



echo "<br/>";

echo vmpcv2('Test php vers js','secret','base64_encode');
echo "<br/>";
echo vmpcv2(vmpcv2('Test php vers js','secret','base64_encode'),'secret','base64_decode');
echo "<br/>";
echo "FIN";

//Reverse javascript
//vmpcv2('0vImGd4mpflgr+fziMsp4Pht7W6LJ7dl','secret','atob')

?>








<script type="text/javascript">

/* RC4 symmetric cipher encryption/decryption
 * Copyright (c) 2006 by Ali Farhadi.
 * released under the terms of the Gnu Public License.
 * see the GPL for details.
 *
 * Email: ali[at]farhadi[dot]ir
 * Website: http://farhadi.ir/
 */

/**
 * Encrypt given plain text using the key with RC4 algorithm.
 * All parameters and return value are in binary format.
 *
 * @param string key - secret key for encryption
 * @param string pt - plain text to be encrypted
 * @return string
 */
function TunnelRC4_JS(key, pt, base64) {
	
	//olivier : modification au code original
	if(base64 == 'atob')
	{
		pt = atob(pt);
	}
	else
	{
		pt = btoa(pt);
	}
	//fin modif
	
	

	s = new Array();
	for (var i=0; i<256; i++) {
		s[i] = i;
	}
	
	
	var j = 0;
	var x;
	
	
	for (i=0; i<256; i++) {
		j = (j + s[i] + key.charCodeAt(i % key.length)) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;
	}
	i = 0;
	j = 0;
	var ct = '';
	
	
	//RC4 ndrop
/*
	//console.log("oliv "+pt.length);
    // Discard the first 2304 bytes of the keystream to mitigate the
    // Fluhrer/Mantin/Shamir attack	
	//olivier : ajouter au code original
	len = pt.length + 2304;
	//fin modif
	
	//olivier : modification au code original
	//for (var y=0; y<pt.length; y++) {
	for (var y=0; y<len; y++) {
	//fin modif
		i = (i + 1) % 256;
		j = (j + s[i]) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;
		
		//olivier : modification au code original
		//ct += String.fromCharCode(pt.charCodeAt(y) ^ s[(s[i] + s[j]) % 256]);
		if (y > 2303) {
			ct += String.fromCharCode(pt.charCodeAt(y - 2304) ^ s[(s[i] + s[j]) % 256]);
		}
		//fin modif	
	}
*/


//SPRITZ
/*
  for (w = 1, z = 0, k = 0, i = 0, n = 0, o = 0; o < pt.length; o++)
  {
    i = (i + w) % 256,
    n = (k + s[(n + s[i]) % 256]) % 256,
    k = (k + i + s[i]) % 256,
    r = s[i],
    s[i] = s[n],
    s[n] = r,
    z = s[(n + s[(i + s[(z + k) % 256]) % 256]) % 256],
    ct += String.fromCharCode(pt.charCodeAt(o) ^ z);
  }
*/	
	

	

//SPRITZ OLIVIER
/*
	var w = 1;

	w = 1, z = 0, k = 0, i = 0, n = 0, o = 0;
	
	for (var y=0; y<pt.length; y++) {

		i = (i + w) % 256;
		j = (k + s[(j + s[i]) % 256]) % 256;
		k = (k + i + s[j]) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;

		z = s[(j + s[(i + s[(z + k) % 256]) % 256]) % 256];

		ct += String.fromCharCode(pt.charCodeAt(y) ^ z);

	}	
*/	

//SPRITZ OLIVIER ndrop	
	len = pt.length + 12789;
	


	w = 133, z = 0, k = 0, i = 0, n = 0, o = 0;
	
	//for (var y=0; y<pt.length; y++) {
	for (var y=0; y<len; y++) {

		i = (i + w) % 256;
		j = (k + s[(j + s[i]) % 256]) % 256;
		k = (k + i + s[j]) % 256;
		x = s[i];
		s[i] = s[j];
		s[j] = x;

		z = s[(j + s[(i + s[(z + k) % 256]) % 256]) % 256];

		//ct += String.fromCharCode(pt.charCodeAt(y) ^ z);
		if (y > 12788) {
			ct += String.fromCharCode(pt.charCodeAt(y - 12789) ^ z);
		}

	}	
	
	//olivier : modification au code original
	if(base64 == 'btoa')
	{
		ct = btoa(ct);
	}
	else
	{
		ct = atob(ct);
	}
	//fin modif
	
	
	return ct;
}





function spritz(t, e, base64) {

	if(base64 == 'atob')
	{
		t = atob(t);
	}
	else
	{
		t = btoa(t);
	}
	
  var i, n, r, a, s, o, z, k, w;
  
  for (i = [], n = 0, a = "", s = 0; s < 256; s++)
  {
	  i[s] = s;
  }
  
  //chainage des commandes après le for sans accolade, mais des virgules entre les commandes!!
  for (s = 0; s < 256; s++)
    n = (n + i[s] + e.charCodeAt((s % e.length))) % 256,
    r = i[s],
    i[s] = i[n],
    i[n] = (r);

 

  for (w = 1, z = 0, k = 0, s = 0, n = 0, o = 0; o < t.length; o++)
  {
    s = (s + w) % 256,
    n = (k + i[(n + i[s]) % 256]) % 256,
    k = (k + s + i[s]) % 256,
    r = i[s],
    i[s] = i[n],
    i[n] = r,
    z = i[(n + i[(s + i[(z + k) % 256]) % 256]) % 256],
    a += String.fromCharCode(t.charCodeAt(o) ^ z);
  }
  
	if(base64 == 'btoa')
	{
		a = btoa(a);
	}
	else
	{
		a = atob(a);
	}		
	
  return a
}





//VMPC


function vmpc(t, e, base64) {
	
	if(base64 == 'atob')
	{
		t = atob(t);
	}
	else
	{
		t = btoa(t);
	}	
	/*
  var i, n, r, a, s, o, s2, v, k;
  for (i = [], n = 0, a = "", s = 0; s < 256; s++) i[s] = s;
  for (k = 0; k < 3; k++)
    for (s = 0; s < 256; s++)
      n = (n + i[s] + e.charCodeAt((s % e.length))) % 256,
      r = i[s],
      i[s] = i[n],
      i[n] = (r);
  for (s = 0, n = 0, o = 0, r = 0; o < t.length; o++)
    r = i[s],
    n = i[(n + r) % 256],
    a += String.fromCharCode(t.charCodeAt(o) ^ i[i[(i[n]+1) % 256]]),
    r = i[s],
    i[s] = i[n],
    i[n] = r,
    s = (s + 1) % 256;
*/



    //Key-scheduling algorithm (KSA)
    for (s = [], i = 0; i < 256; i++)
	{
        s[i] = i;
    }
	
	for (k = 0; k < 3; k++)
	{
        for (j = 0, i = 0; i < 256; i++)
        {
			j = (j + s[i] + e.charCodeAt(i % e.length)) % 256;
            x = s[i];
            s[i] = s[j];
            s[j] = x;
        }
    }
	
	//Pseudo-random generation algorithm (PRGA)
    for (i = 0, j = 0, x = 0, a = '', y = 0; y < t.length; y++)
    {
        x = s[i];
        j = s[(j + x) % 256];
		a += String.fromCharCode(t.charCodeAt(y) ^ s[s[(s[j]+1) % 256]]),	
        x = s[i];
        s[i] = s[j];
        s[j] = x;
        i = (i + 1) % 256;
    }






	if(base64 == 'btoa')
	{
		a = btoa(a);
	}
	else
	{
		a = atob(a);
	}		
	
  return a
}
 
function vmpcv2(t, e, base64) {
	
	if(base64 == 'atob')
	{
		t = atob(t);
	}
	else
	{
		t = btoa(t);
	}	
	
    //Key-scheduling algorithm (KSA)
    for (s = [], i = 0; i < 256; i++)
	{
        s[i] = i;
    }
	
	for (k = 0; k < 3; k++)
	{
        for (j = 0, i = 0; i < 256; i++)
        {
			j = (j + s[i] + e.charCodeAt(i % e.length)) % 256;
            x = s[i];
            s[i] = s[j];
            s[j] = x;
        }
    }
	
	//Pseudo-random generation algorithm (PRGA)
   len = t.length + 4096;
   // for (i = 0, j = 0, x = 0, a = '', y = 0; y < t.length; y++)
    for (i = 0, j = 0, x = 0, a = '', y = 0; y < len; y++)
    {
        x = s[i];
        j = s[(j + x) % 256];
		//comapraison v1
		//a += String.fromCharCode(t.charCodeAt(y) ^ s[s[(s[j]+1) % 256]]);		
        //good v2 :
		//a += String.fromCharCode(t.charCodeAt(y) ^ s[(s[s[j]] + 1) % 256]);
		if (y > 4095) {
			a += String.fromCharCode(t.charCodeAt(y - 4096) ^ s[(s[s[j]] + 1) % 256]);
		}        
		
		
		x = s[i];
        s[i] = s[j];
        s[j] = x;
        i = (i + 1) % 256;
    }
	
	if(base64 == 'btoa')
	{
		a = btoa(a);
	}
	else
	{
		a = atob(a);
	}		
	
  return a
}



</script>
