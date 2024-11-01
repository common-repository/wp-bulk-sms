=== WP - Bulk SMS - by SMS.to ===
Contributors: mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
Donate link: 
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: sms, wordpress, send, subscribe, message, register, notification, sms.to, subscribes-sms, bulksms
Requires at least: 3.0
Tested up to: 6.5
Stable tag: 1.0.12
Requires PHP: 5.6


Improve your WordPress Website: Communicate with SMS using the WP - Bulk SMS - by SMS.to plugin.


== Who We Are ==

SMS.to is a complete SMS Marketing & SMS API gateway platform offering Enterprise grade Omni-Channel digital communication services to businesses.
That includes a sophisticated SMS Marketing platform, OTP (One time passwords), 2 Way SMS, 2FA ( 2 Factor Authentication), notifications and Viber Messaging.


== Our Mission ==

SMS.to Mission is to simplify business communications by disrupting the aged telecom model of lengthy negotiations and processes and allow a quick and efficient go to market strategy through our Robust API's and intuitive Web Platforms.
Businesses can integrate and reach their customers in hours not months from the day of setup, achieving cost and time efficiency and most importantly competitiveness through all the value added features SMS.to provides.

== Plugin Description ==

### WP - Bulk SMS - by SMS.to: A Wordpress plugin for Bulk SMS Messaging/Texting

This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs.
It is completely free to download and use. You just need to have an account in https://sms.to/ 

Using WP - Bulk SMS - by SMS.to plugin you can enjoy many features

= Features =
* Send SMS to WordPress users
* Send SMS to WordPress roles
* Send SMS to a mobile phone number 
* Send SMS to more than one mobile phone number

Send SMS automatically to users and operator in different situations
* Send SMS to Wordpress subscribers When publish new post.
* Send SMS to the author of the post when that post is published.
* Send SMS to Operator mobile phone number when a new release of WordPress.
* Send SMS to Operator mobile phone number and to the user when registers on wordpress.
* Send SMS to Operator mobile phone number when get a new comment.
* Send SMS to Operator mobile phone number when user is login.

= Send SMS to WordPress users = 
Select WP users as the recipients of your message. This will automatically be sent to all WP users mobile phones.

= Send SMS to WordPress roles = 
Select Role as the recipient of your message. This will open a new drop down list to select one of WordPress existing Roles. Your message will me sent to the user belonging to the selected role.

= Send SMS to a mobile phone number = 
Select Single number as the recipient of your message. This will display a new field to enter the mobile phone number.
The country code must be used if the prefix is not activated. i.e +357 or +44

= Send SMS to more than one mobile phone number = 
Select Paste Number(s) as the recipient of your message. This will display a new area field to enter the mobile phone numbers separated by a comma.
The country code must be used i.e +357 or +44

= Send SMS to Wordpress subscribers When publish new post = 
By enabling this option, Wordpress subscribers will receive SMS when a new post is published. 
The following variables can be set in the message content in the Notifications settings
Post title: %post_title%, Post content: %post_content%, Post url: %post_url%, Post date: %post_date%

= Send SMS to the author of the post when that post is published = 
By enabling this option, the author of a post will receive SMS when the post is published. 
The following variables can be set in the message content in the Notifications settings
Post title: %post_title%, Post content: %post_content%, Post url: %post_url%, Post date: %post_date%

= Send SMS to Operator mobile phone number when a new release of WordPress = 
By enabling this option, the administrator will receive SMS when there is a new release of WordPress

= Send SMS to Operator mobile phone number and to the user when registers on wordpress = 
By enabling this option, the administrator and the user will receive SMS when the user registers in wordpress. 
The following variables can be set in the message content in the Notifications settings
User login: %user_login%, User email: %user_email%, Register date: %date_register%

= Send SMS to Operator mobile phone number when get a new comment = 
By enabling this option, the administrator will receive SMS when there is a new comment on a post. 
The following variables can be set in the message content in the Notifications settings
Comment author: %comment_author%, Author email: %comment_author_email%, Author url: %comment_author_url%, Author IP: %comment_author_IP%, Comment date: %comment_date%, Comment content: %comment_content%

= Send SMS to Operator mobile phone number when user is login = 
By enabling this option, the administrator will receive SMS when the user is login in wordpress. 
The following variables can be set in the message content in the Notifications settings
Username: %username_login%, Nickname: %display_name%


== WP - Bulk SMS - by SMS.to plugin Installation ==

* Prerequisites
1. Installation of Wordpress
2. Active sms.to account
3. Set users profile mobile phones

### INSTALL WP - Bulk SMS - by SMS.to

1. Visit the plugins page within your dashboard and select ‘Add New’;
2. Search for ‘WP - Bulk SMS - by SMS.to’;
3. Activate WP - Bulk SMS - by SMS.to from your Plugins page;

### UNINSTALL WP - Bulk SMS - by SMS.to 
1. De-activate WP - Bulk SMS - by SMS.to from your Plugins page;
2. Delete Plugin


### AFTER ACTIVATION

1. Sign in with your sms.to account [SMSto login](https://sms.to/login#/) or Sign Up [SMSto Sign Up](https://sms.to/register#/)
2. Visit [SMSto api](https://sms.to/app#/api/client)  
3. Add funds to your account 
4. Go to API Clients, API Key Authentication, enter a Title and generate an API Key
5. Copy the generated sms.to API Key into the WP - Bulk SMS - by SMS.to plugin - Settings - Gateway - API Key
6. Save

1. Visit Settings - Features - Add Mobile number field - and select it. So, now you can get the mobile phone of new subscribers and
also update existing subscribers/users with their mobile phone.
2. Enable WP User registration to allow get the mobile phone of new subscribers at the time of registration.

You’re done! You can now SMS the world!!


== Frequently Asked Questions ==
= 1.0.0 =
* No FAQ

== Changelog ==
= 1.0.12 =
* Compatibility with WordPress v6.5
* Adjust classes properties
= 1.0.11 =
* Compatibility with WordPress v6.4
* Fix the received charge of messages
= 1.0.10 =
* Compatibility with WordPress v6.3
= 1.0.9 =
* Compatibility with WordPress v6.1.1
= 1.0.8 =
* Optimization of API
* Compatibility with WordPress v6.1
= 1.0.7 =
* Fix the link for information on how messages are charged
* Compatibility with WordPress v5.9
= 1.0.6 =
* Compatibility with WordPress v5.8
= 1.0.5 =
* Added in description as a forked plugin from https://wordpress.org/plugins/wp-sms/ by VeronaLabs
* Added inline documentation that this plugin is a fork from https://wordpress.org/plugins/wp-sms/ by VeronaLabs, credited all authors and added copyright
* Added licence file
* Sender ID Can contain only letters digits and spaces
* Fix ordering in Reports
= 1.0.4 =
* Fix sending of single message to return message_id in response
* Fix Report queries for Messages and Campaigns
* Do not sum to Total messages in Reports - Campaigns if there are no sent, pending or failed messages
* Automatic data refresh on Reports interval changed to query DB only every 24 seconds - 10 times maximum for as long the Reports screen remains open.
= 1.0.3 =
* Compatibility with WordPress v5.7
= 1.0.2 =
* Fix Privacy error on function
= 1.0.1 =
* Admin mobile phone number is renamed to Operator mobile phone number
* Added automatic data refresh on Reports 
* Added links to Report for 'Information on how messages are charged' and 'Contact Support'
* Balance in Admin Menu-Bar is updated automatically when a message is sent
* WP roles users mobiles phones to be selected only if valid.
* Updated Documentation on how messages are charged
* Fix filtering in Reports view
= 1.0.0 =
* First release

== Upgrade Notice ==
= 1.0 =
* No upgrade

== Screenshots ==

1. General
2. Gateway configuration
3. Features page
4. Notifications page
5. Send SMS Page
6. Reports Page
7. Documentation
8. Privacy Page


### Premium support

Sms.to Developers aim to provide regular support for WP - Bulk SMS - by SMS.to plugin through our site https://sms.to
[Contact us](https://sms.to/contact-us)  or [Send us an email](support@sms.to)