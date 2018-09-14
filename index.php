<?php /*

IcyWallet
Accessible Bitcoin cold storage
https://github.com/neatnik/icywallet

This is a pre-release development version and should not be used. See github.com/neatnik/icywallet for details.

Copyright (c) 2018 Neatnik LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */

require_once __DIR__ . "/vendor/autoload.php";

use BitWasp\Bitcoin\Address\AddressCreator;
use BitWasp\Bitcoin\Address\BaseAddressCreator;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Address\ScriptHashAddress;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKey;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Key\PrivateKeyFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use BitWasp\Bitcoin\Script\P2shScript;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Script\WitnessProgram;
use BitWasp\Bitcoin\Script\WitnessScript;

function render($output) {
	echo $output."\n\n";
}

function networked() {
	$connected = @fsockopen("example.com", 80); 
	if($connected) {
		$is_connected = true;
		fclose($connected);
	}else{
		$is_connected = false;
	}
	return $is_connected;
}

function prepare_file($file) {
	touch($file);
	chmod($file, 0777);
}

// First run config items

// Is the directory writable?
if(!is_writable(__DIR__)) {
	die('The current directory is not writeable by IcyWallet. You’ll need to adjust the directory’s permissions before you can use IcyWallet.');
}

//unlink('preferences.json');
//unlink('wallet.json');

// Does the settings file exist?
if(!file_exists('preferences.json')) {
	prepare_file('preferences.json');
	$preferences['typeface'] = 'sans-serif';
	$preferences['text_size'] = 'large';
	$preferences['contrast'] = 'high-dark';
	file_put_contents('preferences.json', json_encode($preferences, JSON_PRETTY_PRINT));
}

// Were new preferences saved?

if(isset($_REQUEST['preferences'])) {
	foreach($_REQUEST as $preference => $value) {
		if($preference == 'preferences') continue;
		$preferences[$preference] = $value;
	}	
	file_put_contents('preferences.json', json_encode($preferences, JSON_PRETTY_PRINT));
}

// Read preferences
$preferences = json_decode(file_get_contents('preferences.json'));

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>IcyWallet</title>
<style type="text/css">
<?php

// Render browser styles

$font = $preferences->typeface;
$contrast = $preferences->contrast;
switch($preferences->text_size) {
	case 'small':
		$size = '1em';
		break;
	case 'medium':
		$size = '2em';
		break;
	case 'large':
		$size = '3em';
		break;
	case 'extra-large':
		$size = '4em';
		break;
	case 'huge':
		$size = '5em';
		break;
	case 'gigantic':
		$size = '6em';
		break;
}

$font_size = !isset($size) ? null : 'font-size: '.$size.';';
$font_family = $font == 'default' ? null : 'font-family: '.$font.';';

switch($contrast) {
	case 'high-dark':
		$font_color = '#ffff66';
		$background_color = '#000';
	break;
	case 'high-light':
		$font_color = '#000';
		$background_color = '#fff';
	break;
	case 'medium-dark':
		$font_color = '#FFCC00';
		$background_color = '#000';
	break;
	case 'medium-light':
		$font_color = '#CC9966';
		$background_color = '#fff';
	break;
	case 'low-dark':
		$font_color = '#996633';
		$background_color = '#000';
	break;
	case 'low-light':
		$font_color = '#999';
		$background_color = '#fff';
	break;
	default:
		$font_color = null;
		$background_color = null;
}

unset($contrast);

$font_color = $font_color == null ? null : 'color: '.$font_color.';';
$background_color = $background_color == null ? null : 'background: '.$background_color.';';


echo "* {
	$font_family
	$font_color
	$background_color
}

input, select, button {
	font-size: 1em;
}

body {
	margin: 2em;
	$font_family
	$font_size
	$font_color
	$background_color
}"

?>

</style>
<body>

<h1>IcyWallet</h1>

<ul>
<li><a href="#main">Skip to main content</a></li>
<li><a href="#accessibility">Skip to accessibility preferences</a></li>
</ul>

<h2>Notice</h2>

<p>This is a pre-release development version and should not be used. See <a href="https://github.com/neatnik/icywallet">github.com/neatnik/icywallet</a> for details.</p>

<?php

if(networked()) {
	render('<h2>Warning</h2>');
	render('<p>This device may be connected to the internet. Proceed with caution and assume that all wallets have been compromised. New wallets should only be generated on disconnected devices that are never subsequently connected to the internet. Be careful out there.</p>');
}

?>

<h2 id="accessibility">Accessibility Preferences</h2>
<form action="?" method="post">

<h3>Interface</h3>

<p>IcyWallet runs in a web browser (with your choice of screen reader and visual style preferences) or can also run with a command line interface from the system shell using its own voice and/or a refreshable Braille display. Changes to this setting will be reflected after restarting the device.</p>

<label for="interface">Preferred Interface</label>
<select name="interface" id="interface">
<option name="browser" value="browser">Web Browser</option>
<option name="cli" value="cli">Shell / Command Line Interface</option>
</select>

<h3>Text Style</h3>

<p><label for="typeface">Typeface</label>
<select name="typeface" id="typeface">
<?php

$typeface['default'] = 'Browser Default';
$typeface['sans-serif'] = 'Sans Serif';
$typeface['serif'] = 'Serif';
$typeface['monospace'] = 'Monospace';

foreach($typeface as $name => $value) {
	$selected = ($name == $preferences->typeface) ? ' selected' : null;
	echo '<option name="'.$name.'" value="'.$name.'"'.$selected.'>'.$value.'</option>'."\n";
}

?>
</select></p>

<p><label for="text_size">Size</label>
<select name="text_size" id="text_size">
<?php

$text_size['default'] = 'Browser Default';
$text_size['small'] = 'Small';
$text_size['medium'] = 'Medium';
$text_size['large'] = 'Large';
$text_size['extra-large'] = 'Extra Large';
$text_size['huge'] = 'Huge';
$text_size['gigantic'] = 'Gigantic';

foreach($text_size as $name => $value) {
	$selected = ($name == $preferences->text_size) ? ' selected' : null;
	echo '<option name="'.$name.'" value="'.$name.'"'.$selected.'>'.$value.'</option>'."\n";
}

?>
</select></p>

<p><label for="contrast">Contrast</label>
<select name="contrast" id="contrast">
<?php

$contrast['default'] = 'Browser Default';
$contrast['high-dark'] = 'High, Dark Background';
$contrast['high-light'] = 'High, Light Background';
$contrast['medium-dark'] = 'Medium, Dark Background';
$contrast['medium-light'] = 'Medium, Light Background';
$contrast['low-dark'] = 'Low, Dark Background';
$contrast['low-light'] = 'Low, Light Background';

foreach($contrast as $name => $value) {
	$selected = ($name == $preferences->contrast) ? ' selected' : null;
	echo '<option name="'.$name.'" value="'.$name.'"'.$selected.'>'.$value.'</option>'."\n";
}

?>
</select></p>

<p><button type="submit" name="preferences" value="preferences">Save Preferences</button></p>

</form>

<?php

function toAddress(HierarchicalKey $key, BaseAddressCreator $addressCreator, $purpose) {
	switch ($purpose) {
		case 44:
			$script = ScriptFactory::scriptPubKey()->p2pkh($key->getPublicKey()->getPubKeyHash());
			break;
			case 49:
				$rs = new P2shScript(ScriptFactory::scriptPubKey()->p2wkh($key->getPublicKey()->getPubKeyHash()));
				$script = $rs->getOutputScript();
			break;
			default:
			throw new \InvalidArgumentException("Invalid purpose");
	}
	return $addressCreator->fromOutputScript($script);
}

prompt:

//$line = readline('Do you want to [g]enerate a new seed, or [r]ecover an existing seed? ');

render('<h2 id="main">Main</h2>');

render('<h3 id="wallet">Wallet</h3>');

// Generate new wallet

if(isset($_REQUEST['generate_wallet'])) {
	
	//if(isset($_REQUEST['destroy'])) {
	//	unlink('wallet.json');
	//}
	
	$random = new Random();
	//$entropy = $random->bytes(64); // Generates a 24 word mnemonic
	$entropy = $random->bytes(16); // Generates a 12 word mnemonic
	$bip39 = MnemonicFactory::bip39();
	$seedGenerator = new Bip39SeedGenerator();
	$mnemonic = $bip39->entropyToMnemonic($entropy);
	$omnemonic = $mnemonic;
	
	render('<p>A new wallet has been created for you. Carefully capture the following 12 mnemonic seed words, which will serve as a backup of your wallet.</p>');
	render('<h4>Mnemonic</h4>');
	
	$mnemonic = explode(' ', $mnemonic);
	
	render('<ol>');
	foreach($mnemonic as $word) {
		render('<li>'.$word.'</li>');
	}
	render('</ol>');
	
	//render('<p>'.$mnemonic.'</p>');
	
	// Derive a seed from mnemonic/password
	$password = '';
	$seed = $seedGenerator->getSeed($mnemonic, $password);
	
	$wallet['mnemonic'] = $omnemonic;
	$wallet['bip_39_seed_hex'] = $seed->getHex();
	$root = HierarchicalKeyFactory::fromEntropy($seed);
	$wallet['bip_39_root_key_private'] = $root->toExtendedKey();
	$wallet['bip_39_root_key_public'] = $root->toExtendedPublicKey();
	
	prepare_file('wallet.json');
	file_put_contents('wallet.json', json_encode($wallet, JSON_PRETTY_PRINT));
	
	render('<p>When ready, <a href="?">confirm that you have captured your mnemonic seed words accurately</a>. Or, <a href="?generate_wallet&rand='.rand().'#wallet">destroy this wallet and generate a different one</a>.');
	exit;
}

// Check for wallet file

if(!file_exists('wallet.json')) {
	render('<p>You have not yet created a wallet. <a href="?generate_wallet#wallet">Generate a new wallet.</a>');
	exit;
}

else {
	$wallet = json_decode(file_get_contents('wallet.json'));
	render('<p>Your wallet is ready to receive funds.</p>');
	$purpose = 49;
	
	$seedGenerator = new Bip39SeedGenerator();
	$seed = $seedGenerator->getSeed($wallet->mnemonic, '');
	
	$root = HierarchicalKeyFactory::fromEntropy($seed);
	
	$purposePriv = $root->derivePath("{$purpose}'/0'/0'");
	$purposePub = $purposePriv->toExtendedPublicKey();
	$xpub = HierarchicalKeyFactory::fromExtended($purposePub);
	$addressCreator = new AddressCreator();
	
	$i = 0;

	render('<p>Your address is: '.toAddress($xpub->derivePath("0/$i"), $addressCreator, $purpose)->getAddress().'</p>');
	
	exit;
			
	$rs = new P2shScript(ScriptFactory::scriptPubKey()->p2wkh($xpub->derivePath("0/$i")->getPublicKey()->getPubKeyHash()));
	$script = $rs->getOutputScript();	
	
	//$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);
	$p2pkh = AddressCreator::fromOutputScript($script);
	$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
	$p2shAddr = $redeemScript->getAddress();
	$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
	$p2wshAddr = $p2wshScript->getAddress();
	
	$address = $p2wshAddr->getAddress();
	
	render('<p>Your address is: '.$address.'</p>');
}

exit;



echo '<h3>Technical Details</h3>';

echo '<div class="details">';
echo 'BIP 39 Seed: '.$seed->getHex()."\n<br>";

//$purpose = 44;
$purpose = 49;

$root = HierarchicalKeyFactory::fromEntropy($seed);
echo "BIP 39 Root Key (Private): " . $root->toExtendedKey() . "\n<br>";
echo "BIP 39 Root Key (Public) " . $root->toExtendedPublicKey() . "\n<br>";

//echo "Derive (m -> m/{$purpose}'/0'/0'): \n";
$purposePriv = $root->derivePath("{$purpose}'/0'/0'");
//echo "m/{$purpose}'/0'/0': ".$purposePriv->toExtendedPrivateKey().PHP_EOL;
//echo "M/{$purpose}'/0'/0': ".$purposePriv->toExtendedPublicKey().PHP_EOL;

echo 'BIP 32 Derivation Path: '." m/{$purpose}'/0'/0'"."\n<br>";
echo '<hr>';
echo "Account Extended Private Key m/{$purpose}'/0'/0': ".$purposePriv->toExtendedPrivateKey()."\n<br>";
echo "Account Extended Public Key M/{$purpose}'/0'/0': ".$purposePriv->toExtendedPublicKey()."\n<br>";

echo '<hr>';

//echo "Derive (M -> m/{$purpose}'/0'/0'): .... should fail\n";
/*
try {
    $rootPub = $root->withoutPrivateKey();
    $rootPub->derivePath("{$purpose}'/0'/0'");
} catch (\Exception $e) {
    echo "caught exception, yes this is impossible: " . $e->getMessage().PHP_EOL;
}
echo "\n\n -------------- \n\n";
*/

$purposePub = $purposePriv->toExtendedPublicKey();

//echo "initialize from xpub (M/{$purpose}'/0'/0'): \n";

$xpub = HierarchicalKeyFactory::fromExtended($purposePub);
$addressCreator = new AddressCreator();

/*

$priv = PrivateKeyFactory::fromWif('L1U6RC3rXfsoAx3dxsU1UcBaBSRrLWjEwUGbZPxWX9dBukN345R1');
$publicKey = $priv->getPublicKey();
$pubKeyHash = $publicKey->getPubKeyHash();

$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);

### Key hash types
//echo "key hash types\n";
$p2pkh = AddressCreator::fromOutputScript($script);
//echo " * p2pkh address: {$p2pkh->getAddress()}\n";

#### Script hash types

echo "\nscript hash types:\n";
// taking an available script to be another addresses redeem script..
$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
$p2shAddr = $redeemScript->getAddress();
//echo " * p2sh: {$p2shAddr->getAddress()}\n";
$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
$p2wshAddr = $p2wshScript->getAddress();
//echo " * p2wsh: {$p2wshAddr->getAddress()}\n";

*/

//echo 'Bech32: '.(new SegwitAddress(WitnessProgram::V0($root->toExtendedKey()->getPubKeyHash())));

$i = 0;
while($i < 20) {
	echo "0/$i: p2sh - ".toAddress($xpub->derivePath("0/$i"), $addressCreator, $purpose)->getAddress();
	//echo "0/$i: ".toAddress($xpub->derivePath("0/$i"), $addressCreator, $purpose)->getKey();
	//echo SegwitAddress(WitnessProgram::V0($xpub->getPubKeyHash()));
	//echo "0/$i: ".toAddress($xprv->derivePath("0/$i"), $addressCreator, $purpose)->getAddress();
	//echo $PrivateKeyFactory::fromWif
	/*
	$priv = PrivateKeyFactory::fromWif('L1U6RC3rXfsoAx3dxsU1UcBaBSRrLWjEwUGbZPxWX9dBukN345R1');
	$publicKey = $priv->getPublicKey();
	$pubKeyHash = $publicKey->getPubKeyHash();
	$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);
	$p2pkh = AddressCreator::fromOutputScript($script);
	$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
	$p2shAddr = $redeemScript->getAddress();
	$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
	$p2wshAddr = $p2wshScript->getAddress();
	*/
	/*
	$priv = PrivateKeyFactory::fromWif('L1U6RC3rXfsoAx3dxsU1UcBaBSRrLWjEwUGbZPxWX9dBukN345R1');
	$publicKey = $priv->getPublicKey();
	$pubKeyHash = $publicKey->getPubKeyHash();
	$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);
	$p2pkh = AddressCreator::fromOutputScript($script);
	$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
	$p2shAddr = $redeemScript->getAddress();
	$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
	$p2wshAddr = $p2wshScript->getAddress();
	echo " - p2wsh: {$p2wshAddr->getAddress()}\n";
	*/
	/*
	$priv = PrivateKeyFactory::fromWif('L1U6RC3rXfsoAx3dxsU1UcBaBSRrLWjEwUGbZPxWX9dBukN345R1');
	$publicKey = $priv->getPublicKey();
	$pubKeyHash = $publicKey->getPubKeyHash();
	$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);
	$p2pkh = AddressCreator::fromOutputScript($script);
	$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
	$p2shAddr = $redeemScript->getAddress();
	$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
	$p2wshAddr = $p2wshScript->getAddress();
	echo ' - ' . $p2wshAddr->getAddress();
	*/
	
	$rs = new P2shScript(ScriptFactory::scriptPubKey()->p2wkh($xpub->derivePath("0/$i")->getPublicKey()->getPubKeyHash()));
	$script = $rs->getOutputScript();	
	
	//$script = ScriptFactory::scriptPubKey()->p2pkh($pubKeyHash);
	$p2pkh = AddressCreator::fromOutputScript($script);
	$redeemScript = new P2shScript($p2pkh->getScriptPubKey());
	$p2shAddr = $redeemScript->getAddress();
	$p2wshScript = new WitnessScript($p2pkh->getScriptPubKey());
	$p2wshAddr = $p2wshScript->getAddress();
	
	echo ' &nbsp; bech32 - ' . $p2wshAddr->getAddress();
	echo "<br>\n";
	
	$i++;
}
#echo "0/0: ".toAddress($xpub->derivePath("0/0"), $addressCreator, $purpose)->getAddress().PHP_EOL;

?>