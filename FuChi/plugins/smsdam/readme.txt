=== About ===
name: SMS Dam
website: http://www.ushahidi.com
description: Provides a new admin screen for a human to determine which SMS messages have potential to become reports.
version: 0.1.1
requires: 2.0
tested up to: 2.0
author: Brian Herbert
author website: http://www.brianherbert.com

== Description ==
This plugin is designed for teams working on deployments that get a lot of SMS messages and need someone to triage them so only messages that have enough information to eventually become a report will get passed on while others will be set aside.

== Installation ==
1. Copy the entire /smsdam/ directory into your /plugins/ directory.
2. Activate the plugin.

== Changelog ==

v0.1.1 - Aug 1, 2010
* Removed deletion of table on uninstall
* Modified create table query on install so it doesn't produce
  an error if it already exists