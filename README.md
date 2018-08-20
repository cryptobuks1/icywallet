# IcyWallet

_Accessible and secure Bitcoin cold storage_

IcyWallet is a Bitcoin cold storage wallet that doesn’t require sight. It aims to be the simplest and most secure Bitcoin cold storage solution with a total emphasis on accessibility. Just plug in headphones and a keyboard, or a refreshable braille display, and get going.

[<img src="https://neatnik.net/bitcoin/icywallet/icywallet_angle.jpg" width="300" height="300" alt="An IcyWallet device, angle view" title="An IcyWallet device, angle view">](https://neatnik.net/bitcoin/icywallet/icywallet_angle.jpg)
[<img src="https://neatnik.net/bitcoin/icywallet/icywallet_overhead.jpg" width="300" height="300" alt="An IcyWallet device, top view" title="An IcyWallet device, top view">](https://neatnik.net/bitcoin/icywallet/icywallet_overhead.jpg)

## Features

* 100% free and open source
* Boots directly into the wallet app with functioning audio and braille support (via [BRLTTY](https://github.com/brltty/brltty))
* All interactions designed to create the best possible accessible experience
* Generates hierarchical deterministic wallets with mnemonic seeds for safe backup
* Generates [SegWit addresses](https://segwit.org)

## How It Works

The IcyWallet software is designed to run on a Raspberry Pi, specifically one that will never be connected to the internet (a so-called [air gapped](https://en.wikipedia.org/wiki/Air_gap_(networking)) device). In theory you could run the software on anything, but everything has been designed with the Pi in mind. IcyWallet will securely generate private keys and store them on the device, and issue a [mnemonic seed](https://en.bitcoin.it/wiki/Mnemonic_phrase) for safe and convenient backup.

## Press

- [This Developer Is Making The First Bitcoin Wallet For The Blind](http://www.ibtimes.com/developer-making-first-bitcoin-wallet-blind-2618126), International Business Times
- [Developer Making the First Bitcoin Wallet for the Blind](https://coolblindtech.com/developer-making-the-first-bitcoin-wallet-for-the-blind/), Cool Blind Tech
- [IcyWallet Offers a Cold Storage Bitcoin Wallet for the Visually Impaired](https://bitcoinmagazine.com/articles/icywallet-offers-cold-storage-bitcoin-wallet-visually-impaired/), Bitcoin Magazine
- [IcyWallet: conheça a carteira fria de Bitcoin para quem é deficiente visual](https://www.tecmundo.com.br/produto/124366-icywallet-conheca-carteira-fria-bitcoin-deficiente-visual.htm), TecMundo
- [Creating the World's First Bitcoin Wallet for the Blind](http://bitcoinist.com/creating-worlds-first-bitcoin-wallet-blind/), Bitcoinist
- [Diseñan cartera fría de bitcoin para invidentes que soporta sistema braille y audio](https://criptonoticias.com/carteras/disenan-cartera-fria-bitcoin-invidentes-soporta-sistema-braille-audio/), CriptoNoticias
- [IcyWallet và nỗ lực mang Bitcoin đến với người khiếm thị](https://bitcoin-news.vn/icywallet-va-no-luc-mang-bitcoin-den-voi-nguoi-khiem-thi/), Bitcoin-news.vn
- [Un développeur travaille à la création du premier portefeuille bitcoin destiné aux personnes non-voyantes](https://www.crypto-france.com/un-developpeur-travaille-a-la-creation-du-premier-portefeuille-bitcoin-destine-aux-personnes-non-voyantes/), Crypto-France
- [Первый биткоин-кошелек для слепых](https://btcnovosti.com/articles/pervyiy-bitkoin-koshelek-dlya-slepyih/), btcnovosti.com
- [IcyWallet für Blinde ist Teil des "Inklusiv-Geistes von Bitcoin](http://www.kryptowaehrunginfo.com/l/icy-wallet-fur-blinde-ist-teil-des-inklusiv-geistes-von-bitcoin/), KryptowährungInfo

## License

This project is licensed under the [MIT License](LICENSE.md).

## Author

IcyWallet is a [Neatnik](https://neatnik.net/) project, by [Adam Newbold](https://adam.lol/).

## Tip Jar

Any donations are sincerely appreciated, and BTC can be sent to 3NJQZrsCEb6RHt1UaXKo6P19XazwNPDvFE.