<?php

/**
 * Change this for the URL of your repository.
 */
define('PRISMIC_URL', 'https://blogtemplate.cdn.prismic.io/api');
define('PRISMIC_TOKEN', null);

/*
 * Disqus integration
 */
define('DISQUS_FORUM', 'prismic-blogtemplate');
define('DISQUS_API_KEY', null);
define('DISQUS_API_SECRET', null);
define('DISQUS_API_ACCESSTOKEN', null);

/*
 * The theme corresponds to a subfolder of ./app/themes/
 */
define('PI_THEME', 'default');

/*
 * Page size: applies for any page containing multiple posts: index, archive, search...
 */
define('PAGE_SIZE', 10);

/*
 * Your site metadata
 */
define('SITE_TITLE', 'prismic.io Website Starter');
define('SITE_DESCRIPTION', 'This is a sample theme using Bootstrap');
define('ADMIN_EMAIL', '');

/*
 * Only change this if you're hacking Blog Template
 */
define('MODE', 'production');
define('DEBUG', 'false');

/**
 * Mailgun API key and domain, for the contact module
 */
define('MAILGUN_APIKEY', 'key-9ff5111148f85af114733af275ed02be'); // API Key, e.g. key-XYZ
define('MAILGUN_PUBKEY', 'pubkey-5e365423532de1374451f86550d3251d'); // Public API KEY, e.g. pubkey-XYZ
define('MAILGUN_DOMAIN', 'sandboxdf80dd347c554c49bb2d116aed33a40f.mailgun.org'); // e.g. sandboxXYZ.mailgun.org
define('MAILGUN_EMAIL', 'wroom-team@zenexity.com');
