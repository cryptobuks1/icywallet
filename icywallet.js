/*

IcyWallet

Copyright (c) 2018 Neatnik LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

--

Version: alpha 0.0.1

--

NOTICE

This pre-release version of IcyWallet runs on the Bitcoin MAINNET and generates live SegWit wallets. Use at your own risk.

--

More information about IcyWallet is available at:

* https://icywallet.com
* https://github.com/neatnik/icywallet

*/

var wrap = require('wordwrap')(80);
var keypress = require('keypress');
var bip39 = require('bip39');
var crypto = require('crypto');
var bitcoin = require('bitcoinjs-lib');
var bitcoinNetwork = bitcoin.networks.bitcoin
const { exec } = require('child_process');
const readline = require('readline');
const rl = readline.createInterface({
	input: process.stdin,
	output: process.stdout,
});

console.log('Test complete.');