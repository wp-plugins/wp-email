-> EMail Plugin For WordPress 2.0
--------------------------------------------------
Author		-> Lester 'GaMerZ' Chan
Email		-> lesterch@singnet.com.sg
Website		-> http://www.lesterchan.net/
Demo		-> http://www.lesterchan.net/blogs/wp-email.php?p=647
Documentation	-> http://dev.wp-plugins.org/wiki/wp-email
Development	-> http://dev.wp-plugins.org/browser/wp-email/
Updated		-> 1st March 2006
--------------------------------------------------
Note: I have changed almost the whole structure of WP-EMail, So if there is any bug,
please contact me immediately.
--------------------------------------------------


// Version 2.03 (01-03-2006)
- NEW: Improved On 'manage_email' Capabilities
- NEW: Neater Structure
- NEW: No More Install/Upgrade File, It Will Install/Upgrade When You Activate The Plugin
- NEW: Added E-Mail Stats Function
- NEW: Per Page Option In email-manager.php
- FIXED: Now Paginate Have Sort Options
- FIXED: Default Mailer Type Is Now PHP
- FIXED: Charset Is Now UTF-8
- FIXED: Quotes Not Displaying

// Version 2.02 (01-02-2006)
- NEW: Added 'manage_email' Capabilities To Administrator Role
- FIXED: Able To View Password Protected Blog

// Version 2.01 (01-01-2006)
- NEW: Compatible With WordPress 2.0
- NEW: EMail A Snippet Of The Post Rather Than The Whole Post. Able To Specify The No. Of Words Before Cutting Off
- NEW: Spam Prevention - Better Checking Of Names, EMail Addresses And Remarks
- NEW: Spam Prevention - Able To Specify The No. Of Mins Before User Is Allowed To Send A 2nd Article
- NEW: GPL License Added
- NEW: Page Title Added To wp-email.php
- NEW: Automated Permalink
- FIXED: Date Not Showing Correctly In EMail Logs
- FIXED: Friend's Name Is Displayed Instead Of Friend's EMail On The Results Page
- UPDATE: Moved All The WP-EMail Functions To wp-includes/functions-wp-email.php

// Version 2.00b (29-11-2005)
- FIXED: Error In Sending E-Mail With Pages

// Version 2.00a (27-11-2005)
- FIXED: exit(); Missing in wp-email.php

// Version 2.00 (20-11-2005)
- FIXED: Did Not Strip Slashes In Remarks Field
- FIXED: All Of WordPress Permlink Styles Should Work Now
- FIXED: Better Localization Support (80% Done, Will Leave It In The Mean Time)
- NEW: EMail Administration Panel
- NEW: EMail Templates Editable Online Via The Administration Panel
- NEW: Change EMail Options Online Via The Administration Panel
- NEW: Every EMail Sent Will Be Logged
- NEW: Uses WordPress Default Query Instead Of Own
- NEW: Uses Most Of The WordPress Functions To Get Data Instead Of Own
- NEW: Able To EMail Page Also
- NEW: If No Remarks Is Made, It Is Known As N/A