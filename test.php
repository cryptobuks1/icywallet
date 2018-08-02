<?php

// IcyWallet development script; not for use.

require_once __DIR__ . "/vendor/autoload.php";

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;

use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;

#use BitWasp\Bitcoin\Address\SegwitAddress;
#use BitWasp\Bitcoin\Key\Factory\PrivateKeyFactory;
#use BitWasp\Bitcoin\Script\WitnessProgram;

echo "\n\nIcyWallet\nPre-release development script; not for use.\n\n";

prompt:
$line = readline('Do you want to [g]enerate a new seed, or [r]ecover an existing seed? ');

if($line == 'g') {
	// Generate a mnemonic
	$random = new Random();
	//$entropy = $random->bytes(64);
	$entropy = $random->bytes(16); // Generates a 12 word mnemonic
	$bip39 = MnemonicFactory::bip39();
	$seedGenerator = new Bip39SeedGenerator();
	$mnemonic = $bip39->entropyToMnemonic($entropy);
	echo "\n".'Your mnemonic is: '.$mnemonic;
	// Derive a seed from mnemonic/password
	$password = '';
	$seed = $seedGenerator->getSeed($mnemonic, $password);
	echo "\n".'Your seed is: '.$seed->getHex();
}
else if($line == 'r') {
	$password = '';
	$mnemonic = readline('Enter your mnemonic: ');
	$seedGenerator = new Bip39SeedGenerator();
	$seed = $seedGenerator->getSeed($mnemonic, $password);
	echo "\n".'Your seed is: '.$seed->getHex();
}
else {
	goto prompt;
}

echo "\n\n";
exit;


// Generate a mnemonic
$random = new Random();
//$entropy = $random->bytes(64);
$entropy = $random->bytes(16); // Generates a 12 word mnemonic

$bip39 = MnemonicFactory::bip39();
$seedGenerator = new Bip39SeedGenerator();
$mnemonic = $bip39->entropyToMnemonic($entropy);


echo "\n\n\n";
echo '-------------------------';

echo "\n\n";
echo $mnemonic;
echo "\n\n";

// Derive a seed from mnemonic/password
$password = '';
$seed = $seedGenerator->getSeed($mnemonic, $password);

echo "\n\n";
echo $seed->getHex() . "\n";
echo "\n\n";


exit;

$hdFactory = new HierarchicalKeyFactory();
$bip32 = $hdFactory->fromEntropy($seed);
$publicKey = $bip32->getPublicKey();
print_r($publicKey);

exit;

$master = $hdFactory->generateMasterKey($random);
$network = Bitcoin::getNetwork();

// To restore from an existing xprv/xpub:
//$master = $hdFactory->fromExtended("xprv9s21ZrQH143K4Se1mR27QkNkLS9LSarRVFQcopi2mcomwNPDaABdM1gjyow2VgrVGSYReepENPKX2qiH61CbixpYuSg4fFgmrRtk6TufhPU");
echo "Master key (m)\n";
echo "   " . $master->toExtendedPrivateKey($network) . "\n";
;
$masterAddr = new PayToPubKeyHashAddress($master->getPublicKey()->getPubKeyHash());

echo "   Address: " . $masterAddr->getAddress() . "\n\n";

echo "UNHARDENED PATH\n";
echo "Derive sequential keys:\n";
$key1 = $master->deriveChild(0);
echo " - m/0 " . $key1->toExtendedPrivateKey($network) . "\n";

$child1 = new PayToPubKeyHashAddress($key1->getPublicKey()->getPubKeyHash());
echo "   Address: " . $child1->getAddress() . "\n\n";

$key2 = $key1->deriveChild(999999);
echo " - m/0/999999 " . $key2->toExtendedPublicKey($network) . "\n";

$child2 = new PayToPubKeyHashAddress($key2->getPublicKey()->getPubKeyHash());
echo "   Address: " . $child2->getAddress() . "\n\n";

echo "Directly derive path\n";

$sameKey2 = $master->derivePath("0/999999");
echo " - m/0/999999 " . $sameKey2->toExtendedPublicKey() . "\n";

$child3 = new PayToPubKeyHashAddress($sameKey2->getPublicKey()->getPubKeyHash());
echo "   Address: " . $child3->getAddress() . "\n\n";

echo "HARDENED PATH\n";
$hardened2 = $master->derivePath("0/999999'");

$child4 = new PayToPubKeyHashAddress($hardened2->getPublicKey()->getPubKeyHash());
echo " - m/0/999999' " . $hardened2->toExtendedPublicKey() . "\n";
echo "   Address: " . $child4->getAddress() . "\n\n";

exit;

?>