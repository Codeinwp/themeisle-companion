# Orbit Fox
[![Build Status](https://travis-ci.org/Codeinwp/orbit-fox.svg?branch=master)](https://travis-ci.org/Codeinwp/orbit-fox)
[![Code Climate](https://codeclimate.com/github/Codeinwp/orbit-fox/badges/gpa.svg)](https://codeclimate.com/github/Codeinwp/orbit-fox)
[![Issue Count](https://codeclimate.com/github/Codeinwp/orbit-fox/badges/issue_count.svg)](https://codeclimate.com/github/Codeinwp/orbit-fox)
[![Test Coverage](https://codeclimate.com/github/Codeinwp/orbit-fox/badges/coverage.svg)](https://codeclimate.com/github/Codeinwp/orbit-fox/coverage)

- **Author URI:** http://themeisle.com
- **Requires at least:** 3.5
- **Tested up to:** 4.8
- **Stable tag:** trunk
- **License:** GPLv2 or later
- **License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Description
> Orbit Fox is a plugin for WordPress that provides an abstraction layer on top of which you can 
write modules to extend its functionality. The plugin provides some predefined workflows and 
methods that work automagically. This means that you can focus more on the logic of your module 
and less on the loading and display of data or files. 

### Goals
 1. **Provide a framework like environment for writing modules:**
    
    Have a predefined set of rules and workflows that work out of the box. This should simplify 
    the way a developer interacts with data, views or files.

 2. **Accessibility:**

    Modules should be easy to write and deploy, also allow for loading of modules from third party 
    developers.

### Specifications
 Have a plugin that can be extended with modules bundled or built by third party developers inside 
 their own plugins. The modules must have an abstract class that defines the implemented methods 
 required and utility methods that simplify the module development.

## Installation
 1. Put the `orbit-fox` folder inside WordPress plugins folder.
 2. Activate via the Plugins WordPress Dashboard.

## Features
 - Easy extensability via modules
 - Support for third party modules
 - Reusable and Utility Classes for faster development

## Development
[Module development guidelines](docs/MODULE.md)

## Docs

[Read the Project Documentation](https://docs.google.com/a/vertistudio.com/document/d/1fFepVs4if5rEmMqA8TiHUFp2WPnkUCW6JZohnsSNtKE/edit?usp=sharing)

## Frequently Asked Questions

// TODO

## Change Log

**v.1.0.0a** 
- Basic structure and module loading workflow. 
- Support for 3rd party modules
- Render Helper
- Model Helper
- Automagic classes

### Contributors
**Bogdan Preda** -- bogdan.themeisle.com
**Marius Cristea** -- marius.cristea@vertistudio.com

**Features:**

- Sharing module
- Reporting module
- More widgets and sections for Hestia Theme
- More widgets and sections for Zerif Theme




## Frequently Asked Questions ##

### How I can get support for this plugin ? ###

You can learn more about Orbit Fox Companion and ask for help by <a href="https://themeisle.com/contact/"  >visiting ThemeIsle website</a>.

### What can I do with this plugin ###

This plugin extends the features of your themes by adding numerous widgets if you are using Zerif and Hestia themes and some modules for sharing and reporting for general use.


## Installation ##

Activating the Orbit Fox Companion plugin is just like any other plugin. If you've uploaded the plugin package to your server already, skip to step 5 below:

1. In your WordPress admin, go to **Plugins &gt; Add New**
2. In the Search field type "Orbit Fox"
3. Under "Orbit Fox Companion" click the **Install Now** link
4. Once the process is complete, click the **Activate Plugin** link
5. Now, you're able to use Orbit fox and setup the modules you need. These can be found at **Tools &gt; Orbit Fox Companion**
6. Make the changes desired, then click the **Save changes** button at the bottom


## Screenshots ##

1. Screenshot 1. How you can enable/disable modules
2. Screenshot 2. How the sharing module is looking
3. Screenshot 3. How reports module is looking

## Changelog ##
### 2.0.11 - 2017-10-19  ###

* Fixed alignment issue for titles in Hestia


### 2.0.10 - 2017-10-18  ###

* Added selective refresh options for the Show/Hide frontpage controls in Hestia
* Make external links open in new tab for the frontpage sections in Hestia
* Added some new filters to control the number of items per row appear in the Features and Testimonials Frontpage section - http://docs.themeisle.com/article/669-how-to-add-4-feature-items-on-a-line-in-hestia


### 2.0.9 - 2017-10-17  ###

* Enhanced layout for Hestia sections.


### 2.0.8 - 2017-10-11  ###

* Fixed bug with URL protocols filter priority.
* Fixed bug with icons background styled by URL address.


### 2.0.7 - 2017-10-02  ###

* New improved options for frontpage sections ordering/disabling in Hestia


### 2.0.6 - 2017-09-19  ###

* Added selective refresh for titles options in the frontpage sections in Hestia


### 2.0.5 - 2017-09-12  ###

* Added new Ribbon and Clients Bar sections in Hestia


### 2.0.4 - 2017-09-11  ###

* Adds PHP minimum requirement. 
* Fix for admin styles loading screen.


### 2.0.3 - 2017-08-24  ###

* Improved compatibility with the new Hestia version.


### 2.0.2 - 2017-08-16  ###

* Fix accordion not opening to display save buttons for modules


### 2.0.1 - 2017-08-14  ###

* Fixed issues with grey icons in Hestia.
* Fixed Recommended Actions flags in customizer.



### 1.0.3 ###

* New widgets for Rhea child theme
* Improved front page selection mechanism for Hestia

### 1.0.1 ###

* Changed tested up to

### 1.0.0 ###

* First version of the plugin

