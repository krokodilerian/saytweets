# Say tweets over the phone

## Purpose

This is a small set of tools to automatically get tweets, convert them to sound and play them via asterisk to some phones.

## How does it work

By using phirehose, `fetchtweets.php` downloads tweets and records them in a directory called 'tweets'.

`saytweets.php` fetches these files one by one, creates a speech file with the tweet (detecting if the language is Bulgarian or English) and tells asterisk to call an extension and play it.

## Setup

* Make sure you've cloned also `phirehose`.
* Create an empty directory `tweets`.
* Copy `config.php.dist` to `config.php` and add the accounts. More info can be found in the `phirehose`
* Setup an extension `speak` in context `speak` that calls the numbers you want to receive the call.
	* Example from OpenFest: `exten => speak,1,Dial(SIP/401&SIP/402&SIP/403&SIP/404)`

## Running

* Start in screen/tmux `fetchtweets.php`
* Start in screen/tmux `saytweet.php`


