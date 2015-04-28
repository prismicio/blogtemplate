<?php

function mailgun_loaded(){
    global $WPGLOBAL;
    return (isset($WPGLOBAL['mailgunApiKey']) &&
      isset($WPGLOBAL['mailgunPubKey']) &&
      isset($WPGLOBAL['mailgunDomain']));
}

function mailgun_pubkey(){
    global $WPGLOBAL;
    return @$WPGLOBAL['mailgunPubKey'];
}

function mailgun_config(){
    $apiKey = (defined("MAILGUN_APIKEY")) ? MAILGUN_APIKEY : null;
    $pubKey = (defined("MAILGUN_PUBKEY")) ? MAILGUN_PUBKEY : null;
    $domain = (defined("MAILGUN_DOMAIN")) ? MAILGUN_DOMAIN : null;
    $recipient = (defined("ADMIN_EMAIL")) ? ADMIN_EMAIL : null;

    if (!$apiKey || !$pubKey || !$domain || !$recipient) return null;
    
    return array(
        'mailgunApiKey' => $apiKey,
        'mailgunPubKey' => $pubKey,
        'mailgunDomain' => $domain,
        'recipient' => $recipient);
}

function mailgun_domain_sha1(){
    if (!defined("MAILGUN_DOMAIN") || !MAILGUN_DOMAIN) return null;
    return sha1(MAILGUN_DOMAIN);
}