<?php

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
	if($base64 == 'base64_decode')
	{
		$pt = base64_decode($pt);
	}
	else
	{
		$pt = base64_encode($pt);
	}		
	
	//fin modif	
	
	
	$s = array();
	for ($i=0; $i<256; $i++) {
		$s[$i] = $i;
	}
	$j = 0;
	$x;
	for ($i=0; $i<256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
	}
	$i = 0;
	$j = 0;
	$ct = '';
	$y;

	
    // Discard the first 2304 bytes of the keystream to mitigate the
    // Fluhrer/Mantin/Shamir attack	
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
		if ($y > 2303) {
			$ct .= $pt[$y - 2304] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
		//fin modif		
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


$cypertext = TunnelRC4_PHP('secret','il pleut','base64_encode');
echo $cypertext;
$plaintext = TunnelRC4_PHP('secret',$cypertext,'base64_decode');
echo $plaintext;
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
function rc4Encrypt(key, pt, base64) {
	
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




//Olivier exemple d'utilisation :

//alert(rc4Encrypt('cle','Coucou ! c\'est oliv'));
//alert(rc4Encrypt('cle',rc4Encrypt('cle','Coucou ! c\'est oliv')));

var toto = rc4Encrypt('secret','il pleut','btoa');
var toto2 = rc4Encrypt('secret',toto,'atob');
alert(toto+'\n'+toto2);


//faire un base64_encode = btoa() et base64_decode = atob() sur le résultat final pour la transmission sur le réseau
//les fonctions nommées base64_encode étant des fonctions php

//La fonction rc4Encrypt et rc4Decrypt sont IDENTIQUES !!! on la rapelle !!
</script>