=== Disable Updates Manager ===
Contributors: kidsguide
Tags: Disable All Updates, Disable Plugin Updates, Disable Theme Updates, Disable WordPress Core Updates, Disable Core Updates, Disable Updates Settings, Disable Updates, Disable All WordPress Updates, Disable All WordPress Updates Settings, Disable Updates Manager, Disable All Updates Manager, Disable Updates Manager Settings
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: trunk

A configurable plugin that disables updates. Easy to customize with 5+ settings.

== Description ==
This plugin is 100% configurable! Check the updates you would like to disable in the settings page.

Their are also other settings to customize.

= Features =
<ol>
<li>Has a simple settings page to disable any type of update.</li>
<li>Has extra settings like remove the "updates" page, remove WordPress core version, or disable background updates. </li>
<li>Disable All Updates setting disables update e-mails, debug e-mails, and more. </li>
<li>Has a link to Support, FAQ, Settings, and the Tutorial.</li>
</ol> 

To see more features view the <a href="http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/screenshots/">screenshots</a>.

= Video Tutorial =
[youtube http://www.youtube.com/watch?v=jAqd0SjLQ_M]

= Just Won't to Disable One or Two? =
With the new settings form under dashboard, it easy. 
Just check the things you won't disabled.

== Frequently Asked Questions ==
= Q: How is this plugin different from the other disable updates plugins? =
A: Their are a couple of differences.
<ol>
<li>They do not disable WordPress automatic updates.</li>
<li>They do not have video tutorials and screenshots.</li>
<li>They do not have settings to make it configurable.</li>
</ol>


= Q: If I remove this plugin from my website will I be able to update my plugins, themes, and WordPress core again? =
A: Yes, this plugin just disables the update (not removes). 


= Q: Is it possible to disable one plugin? Are you considering it? =
A: This plugin does not disable individual plugins or theme. I am considering it but it is hard to do, and I am having trouble with it. 

If you would like to disable just one plugin for now, follow the steps below.

= 1. = Copy the following code into your themes function.php (or child theme).

`function stop_plugin_update( $value ) {
 unset( $value->response['smooth-slider/smooth-slider.php'] );
 return $value;
}
add_filter( 'site_transient_update_plugins', 'stop_plugin_update' );`

= 2. = Get the name of your plugin, and the name of the main plugins file, and put them in the code. 

`function stop_plugin_update( $value ) {
 unset( $value->response['(Name:) smooth-slider/(Main File Name:) smooth-slider.php'] );
 return $value;
}
add_filter( 'site_transient_update_plugins', 'stop_plugin_update' );`

= Note = 
If the plugin that you would like to disable the updates for has changed names, then you must look in the URL to find the proper name.

EX) http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/

You take the last part.

stops-core-theme-and-plugin-updates

= 3. = Refresh the function.php (or functions.php) and it should disable it.

== Screenshots ==
1. Before View Core (Dashboard)
4. Before View Plugin (Plugin Page)
5. After View Plugin (Plugin Page)
2. Before View (Dashboard)
3. After View (Dashboard)
6. Settings Page (Disable Updates Manager)

== Installation ==
Their are two way to install "Disable Updates Manager".

<strong>1)</strong>
<ol>
<li>Push the button above to download the latest version of "Disable Updates Manager".</li>
<li>Go in your websites admin to the Plugins> Add New> page and push Upload.</li>

<li>Upload the zip file and push install.</li>
<li>Go to the Plugins section in your admin dashboard and activate.</li>
</ol>

<strong>2)</strong> <u><strong>Recommended</strong></u>
<ol>
<li>Go in your websites admin to the Plugins> Add New> page and push on the search bar.</li>

<li>Search  "Disable Updates Manager" and push install.</li>
<li>Go to the Plugins section in your admin dashboard and activate.</li>
</ol>

== Changelog ==
= Versions Available for Downloading =
= 3.3.0 =
* Fixed Error with Remove WordPress Version from Footer in Admin
* Added Help Spot in Settings
* Added border-radius Code to Postboxes and Boxes 
* Updated Screenshots
* FAQ Updated

= 3.2.0 =
* Fixed Please Note Spelling Mistake
* Fixed Readme.txt Spelling Mistakes
* Works with WordPress 3.8.1
* Changed Video Tutorial Link
* Added CSS to Postboxes and Please Note Box (changed width, added padding, moved around) 

= 3.1.0 =
* Added "Disable Automatic Background Updates" Setting
* Removed "Please Note!" Postbox
* Added "Please Note" Notice in Settings
* Moved Around Other Settings 
* Screenshots Updated
* Removed Settings (combined into "Disable All Updates" setting)
* Fixed "Other Settings" Postbox "<span>" Code for Settings
* Updated Video Tutorial (using version 3.1.0)

= 3.0.0 =
* Updated Readme.txt Description
* Removed "Other Notes" in Readme.txt 
* Added More Readme.txt Tags
* Added "Disable Debug E-mails" Setting
* Added "Disable Update E-mails" Setting
* Added "Disable WordPress Automatic Updates" Setting
* Added "Remove WordPress Core Version" Setting
* Added "Please Note" Postbox
* Combined "Disable All Updates" Postbox with "Disable Updates" Postbox
* Fixed Half Screen Display
* Removed Language Translate <small>(didn't work)</small>

= 2.7.0 =
* Updated Readme.txt Description 
* Fixed function in settings.

= 2.6.0 =
* Added Better Settings Description
* Fixed Disable All Updates Setting Error <strong>(Thanks conservativeread!)</strong>
* Remove the Updates Page Setting from Disable All Updates Setting <strong>(Thanks again conservativeread!)</strong>

= 2.5.1 =
* Changed Save Button Text
* HTML Notes Updated
* Fixed Changelog Errors
* Updated Description

= 2.5.0 =
* Added Postboxes
* Remove Delete Files Setting
* Added Disable All Updates Setting
* Renamed Name on Settings Page 
* HTML Notes Updated
* Delete Translation .mo File
* Readme.txt Error Fixed

= 2.4.0 =
* Plugin Name Changed (Disable Updates Manager)
* Tags Updated (Make it easier to find this plugin)
* Fixed Some HTML
* Added Translation (Test)
 
= 2.3.0 =
* Remove File Setting Added
* Fixed up Settings Page
* Updated Readme.txt
* Removed Tutorial on Settings Page (their is a link to the tutorial in the plugin page)

= 2.2.1 =
* Fixed Readme.txt Errors with Boxes

= 2.2.0 =
* Settings Page Updated (Added Tutorial to Settings Page)
* Video Tutorial Updated
* New Settings Page Name (Disable Updates)

= 2.1.0 =
* Settings Page Updated (under Dashboard and with more settings)
* Fixed Disable Plugin, Theme, and Core Disabling Problem 
* Updated Readme.txt
* Removed Hide Updates Notice Setting
* Remove Admin Notice <strong>(Thanks conservativeread!)</strong>

= 2.0.0 =
* Settings Page Added
* Disable One, Two, or Three Feature Added
* Updated Screenshots
* Updated Readme.txt

= 1.9.0 =
* Admin Notice Added
* FAQ Error Fixed
* HTML Notes and Some HTML Updated
* Links Added Under Plugin in Dashboard (Support Forum, FAQ, Tutorial)
* Updated Readme.txt

= 1.8.0 =
* FAQ Added
* HTML Notes Edited
* Updated Readme.txt

= 1.7.1 =
* Screenshot Error Fixed
* Installation Error Fixed

= 1.7.0 =
* New Html Code Added (this code disables WordPress automatic updates)
* New Version Numbers (now going up by .1.0)
* Updated Readme.txt (mostly under Installation)

= 1.6.0 =
* Updated Readme.txt
* Fixed HTML Error
* Video Tutorial Added

= 1.5 =
* Updated Readme.txt
* Fixed HTML Spacing

= 1.4 =
* Updated Reame.txt
* Updated HTML Notes

= 1.3 =
* Updated Readme.txt
* Added a Disable Updates Check Feature

= 1.2 =
* Updated Readme.txt
* Updated HTML Notes
* Added Disable Update E-mails HTML (Note: This code will only work for some plugins)

= 1.1 =
* Screenshots Updated
* Fixed Error

= 1.0.0 =
* Fixed Changelog Problem
* HTML Changed
* Updated Readme.txt
* Works with WordPress 3.8

= 0.1 =
* Published on Wordpress.org
 (September 1, 2013)
 
= Note =
Versions 0.2 to 0.9 are not listed in this changelog.