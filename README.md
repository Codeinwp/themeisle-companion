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