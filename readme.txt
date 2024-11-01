=== Simple Clinic ===
Contributors: pixelovely
Donate link: https://ko-fi.com/kim
Tags: avada, fusion builder, medicine, medical, practitioners, modalities, modality
Requires at least: 5.4.0
Tested up to: 6.1.1
Requires PHP: 7.2
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds providers / specialties and custom blocks. Easily create a website for a medical office with many different types of care under one roof.

== Description ==

Make a website for a medical office or collective in a snap. I build this kind of website for clients very frequently, and now I am sharing one of the ways I do it with the community.

Adds the "provider" post type, and the "specialty" category -- however, you can swap the names to "practitioner" and "modality," which I've found to be somewhat more popular among complimentary medicine providers like acupuncturists or chiropractors.

Upload headshots, set biographies, and specify professional suffixes (all those letters after a doctor's name!) and titles for each provider. Create directories of providers, either in total or per specialty.

Adds custom Wordpress Blocks to display your practitioners and specialties wherever you like.

= Special integrations with the Avada Theme & Fusion Builder plugin =

This plugin adds the "Provider" and "Specialty" fusion builder elements, with various easy customization settings.

Puts professional suffixes and title into the Avada Page Title Bar on single provider pages.

== Installation ==

1. Upload the plugin directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add Providers through the new post type, and categorize them into one or more "Specialties"
1. Drop in the "Provider" and "Specialties" Wordpress blocks onto any page where you need them.

== Theming ==
If you are working on a custom theme and want to make your own layout for providers and specialties pages, I recommend you create the following page templates to add to your theme folder:

* single-provider.php
* taxonomy-specialty.php

I also notice that commonly, provider name sizes and margins need adjusting. Here's a snippet of css to add to your theme to get you started:

`h3.provider-title {
  font-size: 25px;
  margin: .5rem 0;
}`

Adjust values to taste. :)

== Frequently Asked Questions ==

= How do I add an image to specialties? =

Please install the wonderful (and free) plugin "[Categories Images](https://wordpress.org/plugins/categories-images/)" by Muhammad El Zahlan to enable images for specialties.

= My provider pages show 404 =

Please visit the Settings > Permalink page in the Wordpress Admin to refresh your permalinks.

= How do I change the post type name from providers to practitioners? =

Go to Settings > Simple Clinic Settings. The settings you need are there. You may need to refresh your permalinks after making changes.

= Do the block names change too? =
No, sorry. The blocks will always be called "Providers" and "Specialties" in the WP admin.

== Screenshots ==

1. An example row of providers
2. Editing panels of the two custom Wordpress Blocks, Providers and Specialties
3. Screenshot of the plugin in use on an actual client site; names and faces removed.
4. A Specialties block in action

== Changelog ==
= 1.0.3 =
Tested up to 6.1.1

= 1.0.2 =
* Updates to escaping some outputs.

= 1.0.1 =
* Fix to PHP shorttags usage

= 0.4 =
* Fix to Avada column display of specialties
* Fix to displaying taxonomy images in Avada
* Allowing the exclusion of specific specialties from Avada blocks

= 0.3 =
* Minor update to get Avada custom blocks working again.

= 0.2 =
* Added "turn off ordering practitioners by last name" to plugin settings, so that you can use a custom ordering plugin with Simple Clinic if you prefer.
* Swapped out wp_make_content_images_responsive() which was deprecated in 5.5 for the new function wp_image_add_srcset_and_sizes()

= 0.1 =
* Plugin first released.

== Show your appreciation for this plugin ==
If I've helped you, please consider [buying me a slice of pizza to fuel my coding](https://ko-fi.com/U6U31XPQI) .
