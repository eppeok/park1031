=== WP ADA Compliance Check Basic - Most Comprehensive Web Accessibility Solution for WordPress ===
Contributors: seshelby
Donate link: https://www.alumnionlineservices.com/
Tags: accessibility, ada, section 508, classicpress, accessability, ada compliance, web accessibility, usability, wcag 
Requires at least: 4.6
Tested up to: 6.1
Requires PHP: 5.5
Stable tag: 3.0.7 
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
WP ADA Compliance Check is the most comprehensive web accessibility solution for WordPress. Trusted by thousands of small business, government and educational institution websites to comply with Section 508 and WCAG 2.1 LEVEL A/AA Web Accessibility Standards. Our WordPress Web Accessibility plugin includes 81 individual error checks, far more than comparable solutions. It evaluates content for Web Accessibility issues anywhere on your website. It integrates seamlessly into your workflow, evaluating your website for Web Accessibility issues when content is published or you can run a complete scan of your website to identify issues in all of your content. Accessibility reports provide references and easy to follow instructions. No other solution compares in ease of use. The basic version is limited to 15 posts or pages during full scans, has a smaller rule set (52 error checks) and is unable to identify issues found in theme files. The full version corrects many common issues automatically using convenient, time saving filter options built into the plugin. [Visit our website to compare versions and review a complete list of features.](https://www.alumnionlineservices.com/) 

**Works with any website editor, including:**
**Gutenberg Compatible**
**Elementor Compatible**
**Beaver Builder Compatible**
**ClassicPress Compatible**
**Divi Compatible**

**[PRO]**  Scans the entire website: posts, pages, custom post types, theme files, widgets, shortcodes, custom fields, terms, menus, excerpts, archives, iframes, PDF files, css files and even linked pages.
**[PRO]**  Many issues are corrected automatically, saving you time and money.
**[PRO]**  Includes many WordPress improvements and time saving features.
**[PRO]**  Includes a web accessibility widget to enhance the accessibility of the website.
**[PRO]**  Reports may be printed, emailed or exported in Excel format and include references and easy to follow instructions.
**[PRO]** Identifies untagged PDF files.
**[PRO]** Integrates with our free HTML Validation plugin to pinpoint HTML validation errors that result in inaccessible web pages.
**[PRO]** Includes 80 accessibility checks, many that are not available in the free version (the free version currently has 52 checks).

[Visit our website to learn more about the features included in the PRO version.](https://www.alumnionlineservices.com/) 

== Installation ==
1. Upload the wp-ada-compliance-check-basic folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==
= Why use the WP ADA Compliance Plugin? =

It’s the law! In 1998, Congress amended the Rehabilitation Act of 1973 to require Federal agencies to make their electronic and information technology accessible to people with disabilities. This law applies to all Federal agencies, some state and local agencies as well as any entity creating websites for applicable agencies.

In recent years, there has been an increase of website accessibility lawsuits. In some states the rulings have indicated that business websites failing to meet WCAG guidelines are in violation of Title III of the ADA.

There is also a strong business case for accessibility. Studies show that accessible websites have better search results, reduced maintenance costs, and increased audience reach. All of which increases profit margin for a business. 

= What issues does WP ADA Compliance Check Basic check for? =

The WP ADA Compliance Plugin evaluates content for the most common issues typically found on your website. As Web Accessibility standards evolve the plugin is continually updated to include the latest requirements.

[Visit our website to compare versions and review a complete list of features and issues that are evaluated](https://www.alumnionlineservices.com) 

= Can WP ADA Compliance Check Basic scan all of my content? =

The basic version supports scanning of up to 15 pages on a website. When a page or post is saved the content is scanned and errors are noted on the editor screen.  There is no limit on content scanned when saving a page or post.

[Visit our website to compare versions and review a complete list of features](https://www.alumnionlineservices.com) 

= How can I upgrade to the full version of WP ADA Compliance Check? =

[The full version may be purchased on our website.](https://www.alumnionlineservices.com)

= What are the System/Server Requirements? =

1. Linux/Unix, Apache webserver with MYSQL and PHP 5.6, PHP 7 >, or PHP 8.
1. PHP Settings: max_execution_time: 300, memory_limit: 256MB
1. Tested on Wordpress 4.6 >
1. Tested on Classic Press 1.0 >
1. Browser support: Windows with Edge, Opera, Firefox or Chrome or Mac with Safari, Opera, Firefox or Chrome  

= How do I troubleshoot 500, 502 or 504 Server Errors During Scans? =

These errors normally occur while scanning pages with a large number of links. If you see server errors, increase the PHP max_execution_time to at least 300 and memory_limit to 256MB or 700MB for PDF scans. It may be necessary to increase the max_execution_time and memory_limit even further if errors persist.

[View additional troubleshooting tips](https://www.alumnionlineservices.com/category/troubleshooting/)

= Does the plugin work with the new Wordpress Gutenberg Editor? =

Yes, the WP ADA Compliance Plugin works with the new Gutenberg editor. Please note, the Gutenberg editor table block tool does not create accessible tables. To add table headers users should insert tables with the classic block or install the Classic Editor Plugin to restore your editor to the original editor experience.

= Does the plugin work on ClassicPress? =

Yes, the WP ADA Compliance Plugin has been tested to work with ClassicPress.

= Additional FAQ =

[View additional FAQ](https://www.alumnionlineservices.com)

== Screenshots ==
1. The Web Accessibility report screen summary includes a summary breakdown of the errors that have been found on your website.
1. The Web Accessibility report screen includes a list of errors found on the website. Each row includes a description of the error, code view and options for viewing or correcting the issue.
1. The ADA Compliance Guidelines Reference shows a list of issues that content is evaluated for along with a reference and instructions for resolving them when encountered. 
1. When a page/post is saved it is scanned for problems and a list of issues is displayed at the top of the screen.
1. Links to the WAVE Web Accessibility Evaluation tool can be used to browse your website to identify potential problems in your theme files.

== Changelog ==
= 3.0.7 =
1. corrected various notices and warnings in PHP8
1. corrected title search filter not working on printed report
1. corrected reports not showing after filtering on a specific post and them clearing the filter
1. updated visual focus removed to comply with new WCAG 2.2 contrast ratio requirements
1. changed version number check to use admin_init
1. removed the requirement to use onkeypress with onclick handlers, no longer required
1. corrected dismissable notices being redisplayed
1. upgraded persist admin notices dismissal plugin to the latest release
1. added user notes link to report screen
1. updated adjacent identical link and redundant anchor text checks to identify anchors tags with a title attribute that is identical

= 3.0.6 =
1. corrected missing database table error during deep scan

= 3.0.5 =
1. corrected report not refreshing after error is ignored

= 3.0.4 =
1. added check for option to seek accessibility help
1. improved accuracy of ambiguous anchor tag check by including the title text only if it isn't the same as the linked text.
1. corrected undefined variable error when closing plugin details
1. corrected empty link check not finding inaccessible empty links when they include a title or aria-label
1. added support for heading roles during missing heading check
1. added support for heading roles during incorrect heading order check
1. corrected javascript uncaught type error on error screen when viewing affected code 
1. changed font awesome enqueue url from kit to free CDN
1. changed enqueue name for dissmissible notices plugin to avoid conflicts
1. correted incorrectly named function in visual focus removed check
1. corrected bug in redundant alt text check
1. improved accuracy of link to non-html content check
1. added support for svgs to redundant title tag rule
1. added support for SVG images to redundant alt text rule

= 3.0.3.1 =
1. minor updates to error descriptions

= 3.0.3 =
1. corrected block editor scan keeping widgets screen from loading
1. corrected errors not displaying on report screen inside page editor

= 3.0.2 =
1. changed file extension on javascripts from php to js to avoid some servers blocking execution 

= 3.0.1 =
1. added additional invalid image alt text checks
1. incorrect heading order check is now more accurate
1. added check for h1 not found inside the main content area
1. added check for Elementor carousel set to autoplay
1. improved accuracy of list with incorrect markup rule
1. added check for links that include an aria-label and title with the same value
1. improved the accuracy of missing and unlabeled landmark checks
1. added check for images hidden from screen reader users but that include alternate text

= 3.0 =
1. enabled deep scan and lowered the maximum posts to scan on full scan demo to 15 to support the deep scan option
1. added check for missing skip links
1. added check for missing landmarks
1. added check for form fields with more than one associated labels
1. added check for animated pngs
1. added check for Elementor background videos
1. added check for missing or invalid ids referenced by aria attributes (i.e... aria-labelledby, aria-describedby)
1. added exclusion for scanning draft and private pages
1. added check for inaccessible Elementor toggle and accordion widgets
1. improved absolute font size check by including affected element code in addition to to the style attribute
1. improved missing form field id check by ignoring fields that are hidden from screen readers
1. improved missing form label check by ignoring fields that are hidden from screen readers
1. improved accuracy of redundant alt text check
1. added check for inline links without a contrast ratio of at least 3:1 with surrounding text
1. added check for titles on images that include filenames
1. corrected bug in color contrast checks
1. corrected false positive on missing td headers
1. corrected check for aria attributes with blank space not being identified when checking for invalid alt text
1. corrected bug creating false positives on releated form fields not grouped check

= 2.3.12 =
1. corrected 500 server error do to missing function when checking image linked to self


= 2.3.11 =
1. added check for inaccessible Elementor Table of Contents widgets 
1. added exclusion for certain tracking pixles when checking for images without alt text
1. removed option to disable jquery use on report screen
1. added check for visual focus removed using javascript "this.blur()"
1. removed check of missing alt text on images inside anchor tag because it was a duplicate of the empty anchor check
1. added support for svg files in various checks such as missing and empty alt text and empty anchor tags
1. added additional invalid alt text checks
1. added exceptions during missing form field id and label checks for hidden/spam block fields
1. added check for empty option tags
1. added option to set assumed forground color to improve color contrast checks in the content editor
1. updated embed missing alternate text check
1. corrected embed content errors not being displayed on report screen
1. added embed tags to remote video source check
1. corrected link to images with accessibility issues
1. improved check for missing lang attribute inside page content

= 2.3.10 =
1. removed fontawesome css from public pages
1. moved remote source audio/video player equivalent text check to its on error type
1. increased database field sizes on id fields
1. improved visual focus removed check to include recently added focusable elements
1. corrected bug resulting in PHP errors during missing fieldset check
1. corrected miscellaneous PHP notices during website scans
1. corrected visual focus removed errors in inline styles not being detected

= 2.3.9 =
1. added additional ambiguous link text checks
1. corrected WARNING that was labeled as ALERT
1. improved input validation
1. corrected bug resulting in lists created with Unicode hyphens in the list with incorrect markup check not being found

= 2.3.8 =
1. added exclusion for Elementor pagination when doing check for ambiguous link text
1. improved the accuracy of visual focus removed checks 
1. improved check for related form fields not grouped with fieldsets and legends
1. improved the accessibility of thickbox popups on the error report screen
1. added aria support for missing alt text inside anchor check
1. added aria support for adjacent identical link check
1. added aria support for redundant anchor text check
1. added aria support for empty anchor tag check
1. added aria support for empty button check
1. added aria support for ambiguous anchor tag check
1. improved list with incorrect markup check to ensure broader coverage of issues
1. added group and radiogroup support to check for fields not grouped with fieldsets and legends
1. added aria-describedby support for missing form label check
1. converted font awesome 4 to font awesome 6 free
1. added check for links to non html documents such as PDF and MS Word without visual cue
1. corrected bug related image icons used to indicate links opening new windows not being identified
1. corrected bug resulting in full error report being emailed instead of keyword searched results
1. performance and code enhancents in scanning process
1. corrected bug resulting in style attributes not being checked for visual focus removed 
1. corrected bug resulting in false positive adjacent identical link and redundant anchor text errors when an image is used inside an anchor tag along with linked text

= 2.3.7 =
1. update JQUERY ajax code to support Wordpress 5.5

= 2.3.6 =
1. added check for related form fields not grouped with fieldsets and legends
1. added W3C html validation link to editor screen and page/post lists
1. added WAVE evaluation link to page/post lists
1. added check for empty table headers <th></th>
1. added check for aria-hidden set to true before reporting empty or missing image alt text
1. added media library filter for all images with accessibility issues
1. disabled content scans during Wordpress importer activities
1. updated table header scope check to account for simple tables with only one header row or column
1. applied changes to support advancements in screen readers related to use of the title attributes 
1. updated ambiguous link text check to recognize title attributes on links, to reduce false positives
1. updated redundant anchor text check to more accurately reflect the issue of ajacent links that are confusing to screen reader users.
1. added support for image tags inside redundant anchor tags
1. added check for adjacent anchor tags with the same link text.
1. improved the accessibility of thickbox links
1. corrected but resulting in false positives for redundant anchor text when link encloses image tags
1. corrected false positive on unlinked email address when string includes @ with spaces before and after

= 2.3.5 =
1. added check for inaccessible Elementor Galleries
1. updated unlinked anchor check to locate unlinked email addresses with space before or after the @ sign
1. added check for jump menus that trigger a page change using javascript event handlers
1. corrected bug resulting in errors in Elementor and Beaver Builder editor content not being found during full scan

= 2.3.4 =
1. updated redundant alt text instructions to reflect current web accessibility standards
1. corrected bug resulting in single page error report on some screens being cut off on the left
1. corrected bug resulting in ignored errors not being visible in single page print reports

= 2.3.3 =
1. corrected bug resulting in database error upon activation

= 2.3.2 =
1. updated link visual cue error description to better reflect actions required
1. corrected bug resulting in help links on error reference not being clickable

= 2.3.1 =
1. removed empty table headers from plugin options page and improved experience for screen reader users
1. added check for complex data tables that may not be correctly interpreted by screen readers
1. added exclusion for ninja tables when reporting missing TD and TH cells
1. corrected 500 error when checking for active plugin
1. removed dashboard messages and related settings

= 2.3 =
1. corrected false positive on color contrast when color name is used with the !important attribute
1. corrected help links not opening on error report
1. corrected PHP Error on dashboard message widget
1. corrected false positive on color contrast check
1. corrected false positives for ambiguous anchor text on audio embed tags
1. added allowance for use of aria-label instead of title on iframe elements
1. added exclusion for button inputs when checking for form fields with missing labels
1. added additional checks for unlinked anchors

= 2.2.11 =
1. added support for the Beaver Builder editor
1. added support for Elementor editor
1. added check for unlinked email addresses and anchors
1. added check for form field labels with a for attribute that does not match a field id
1. added additional ambiguous link text checks
1. added exclusion for scheduled posts when checking for duplicate titles
1. added support for simpledom and corrected bug resulting in false positives on empty heading tags enclosing invalid html
1. corrected bug resulting in false positives on incorrect use of white space
1. corrected bug resulting in false positive on empty anchor and button tags
1. corrected bug in alternate text check on images in PHP version 7.3
1. corrected bug resulting in css issues not being identified when a file contains more than one selector with the same name
1. corrected bug resulting in deleted posts records being left in error reports
1. corrected javascript conflict in block editor

= 2.2.10 =
1. corrected bug with shortcode conversion resulting in operator not supported for strings error

= 2.2.9 =
1. added check to exclude errors on images missing alt text but wrapped in an anchor tag with valid alternate text
1. added check for ambiguous button tags
1. added additional checks for incorrect use of white space
1. added settings link to installed plugin list
1. added check for images, not enclosed in a link, with redundant alt and title attributes
1. added additional checks for invalid alt text
1. corrected bug resulting in some image errors not being reported
1. corrected bug resulting in missing skip nav links not being detected under certain situations
1. corrected display of sample code on color contrast errors
1. corrected bug resulting in empty anchor tag false positives in some content
1. corrected problem with site health check failing loopback and REST API requests

= 2.2.8 =
1. added check for empty href attribute on links
1. corrected issue with pagination links not displaying correctly when error report refreshes dynamically
1. corrected javascript error when tabs not present on error screen 
1. corrected bug causing view code links to stop working after error report is refreshed

= 2.2.7 =
1. added option to disable display of dashboard messages 
1. added addition checks to exclude correctly formatted pagination links from ambiguous link text check
1. corrected false positives on empty links, buttons and headings with enclosed images that have alt text that begins with a blank space

= 2.2.6 =
1. error results for deleted posts are now being removed
1. added exclusion for empty form labels that are hidden from screen readers
1. added check for empty button tags
1. added additional checks for ambiguous link text
1. added additional checks for invalid alt text
1. corrected form field graphical view not available under "view code" on report screen
1. added additional checks for missing form fields labels
1. added additional checks for image name used as alt text on images
1. added check for tables with header cell ids that are not used inside a headers attribute within the table
1. corrected issue with display of dashboard summary
1. corrected bug with Incorrect mime type script error being returned
1. corrected bug in Guttenburg editor where error message was being displayed two times
1. misc bug corrections

= 2.2.5 =
1. added additional checks for invalid alt text on images
1. improved performance of database upgrades for larger websites
1. misc bug corrections

= 2.2.4 =
1. added option to reset default settings on plugin settings screen
1. added option to enable or disable jquery interaction on report screen
1. added option to set the number of errors displayed on report screen
1. added option to hide error summary on report page
1. added dynamic updates to report screen as content is changed or ignored 
1. corrected issue with accessibility report button not being displayed on edit screen when issues exist.
1. misc bug corrections

= 2.2.3 =
1. minor updates

= 2.2.2 =
1. corrected block editor javascript error
1. changed minimum PHP requirement to version 5.5
1. verified support for ClassicPress users
1. added support for websites that share a single database
1. misc bug corrections

= 2.2 =
1. added option to allow retention of plugin data and settings when upgrading the plugin by deleting and uploading a new version
1. added support for websites installed on a subdirectory i.e... http://www.mydomain.com/folder/
1. moved Evaluate with WAVE link to sidebar instead of in TinyMCE editor
1. added check for empty form field labels
1. added check for links with event handlers and no href attribute
1. added check for redundant title attributes on image tags (title that is the same as alt text)
1. added check for redundant title attributes on links around images (title on link that is the same as the images alt text)
1. corrected issue with block editor reporting issues when they are set to be ignored
1. corrected issue with ignored issues returning after a post is resaved
1. added check for skip link not the first link on a page

= 2.1 =
1. misc bug corrections

= 2.02 =
1. misc bug corrections

= 2.01 =
1. misc bug corrections

= 2.0 =
1. added check for justified text
1. added check for images hidden from screen readers using role="presentation" but that include alternate text
1. added check for required fields not identified in forms
1. added check with "WAVE Web Accessibility Evaluation tool" button to TinyMCE editor
1. added support for Gutenberg editor
1. added check for complex tables
1. added check for nested tables

= 1.9 =
1. added check for links or form fields where visual focus indication has been removed
1. added check for tags with event handlers used to emulate links
1. added check for meta refresh attributes that reload the page or redirect to a new location after a timeout
1. added check for heading tags nested incorrectly (ie... H1 before H2, H2 before H3 etc...)
1. added check for tab order being modified using the tabindex attribute
1. added check for elements with pointer specific event handlers but no equivalent keyboard event handler
1. corrected bug causing a conflict with the Wordpress theme editor

= 1.8 =
1. added check for media library images with alt text containing a filename
1. added check for incorrect use of linebreaks to create white space
1. moved reset data option to settings page to reduce likely hood of inadvertent purging of data
1. added check for images added using the CSS background-image property
1. added check for images with empty alternate text attributes and non-empty title or aria-label attributes
1. added check for foreground and background colors without enough contrast
1. added check for tables with summary attributes that duplicate caption
1. added media library filter for invalid alt text
1. added check for images linking to self
1. added check for media library images with alt text that includes "image of", "graphic of" or a similar phrase
1. added check for missing lang attributes inside page content
1. added check for lists with incorrect markup
1. added check for nested fieldsets
1. added check for fieldsets without legends
1. added check for links without a visual cue
1. separated empty alternate text check from missing alternate text to make it easier to distinguish images that are for decoration only
1. added check for form fields that trigger an unexpected context change
1. added check for elements with onclick handler but missing onkeypress handlers
1. issue with certain issues not being displayed when publishing a post but visible in the error report
1. references updated for WCAG 2.1
1. corrected bug on certain websites not allowing the settings page to be loaded
1. other misc bug corrections

= 1.7.1 =
1. corrected issue with wp_enqueue_style of style sheets

= 1.7 =
1. added additional filtering and sorting options to the report screen
1. added option re-check issues from report screen and check for issue link on post/page lists
1. added option to set the minimum user level who can adust settings
1. added settings to allow selection of issues to be monitored
1. added email reports
1. added check for redundant link text with different destinations
1. added check for image map area tags without alternate content
1. misc bug corrections

= 1.6.1 =
1. check for file name used as alternate text in images
1. misc bug corrections

= 1.6 =
1. added check for audio and video tags set to auto play
1. added check for embed tags missing alternate text
1. added print friendly reports
1. misc bug corrections

= 1.5.1 =
1. added check for urls used in link text 
1. added check for data tables marked as presentation only
1. added check for table cells referencing a non-existent header id
1. added dashboard messages

= 1.5 =
1. improved alt text check to avoid false positives when alternate text is included inside captions
1. added custom field content validation
1. added check for links opening in new windows without first notifying users
1. added options to automatically correct links opening in a new window
1. added option to automatically remove redundant alt text on images with captions and links with title attributes
1. corrected bug with automatic content updates 

= 1.4 =
1. unlocked the ignore option
1. unlocked the scan feature for page content
1. unlocked the purge feature
1. added option to automatically convert absolute font units in content to relative units
1. added reference links for section 508 and wcag 2.0 requirements
1. added theme validation with webaim 
1. added check for title attributes that duplicate content within the body of the link
1. added check for image alt text that includes Image of, Graphic of or similar phrases
1. added a check for redundant alt text
1. added issue summary to error log
1. added check for blank or duplicate page titles
1. added filter for post type and error type on error log
1. added check for audio and video tags missing equivalent text
1. added check for form fields missing labels and aria-labelledby attributes
1. corrected bug with translation of help topics

= 1.3 =
1. added setting to automatically convert font size selector to relative size (% or em)
1. corrected bug not allowing post types to be selected on settings page for multi-site networks
1. corrected bug not allowing post types without existing posts to be displayed on settings page

= 1.2 =
1. removed post types from settings page if they do not support the editor field 
1. added error references and help links
1. added pagination to results page
1. added media library image scanner
1. added check for object tags missing equivalent text
1. some cosmetic upgrades

= 1.1 =
1. added absolute font size check
1. added settings page with option to select post types and terms to be scanned
1. error list now displays actual post type instead of term or post

= 1.0 =
Initial Deployment

 == Upgrade Notice ==

