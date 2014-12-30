=== StatsFC Top Scorers ===
Contributors: willjw
Donate link:
Tags: widget, football, soccer, premier league
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This widget will place a live Premier League top scorers table in your website.

== Description ==

Add a Premier League top scorers table to your WordPress website. To request a key sign up for your free trial at [statsfc.com](https://statsfc.com).

For a demo, check out [wp.statsfc.com/top-scorers/](http://wp.statsfc.com/top-scorers/).

== Installation ==

1. Upload the `statsfc-top-scorers` folder and all files to the `/wp-content/plugins/` directory
2. Activate the widget through the 'Plugins' menu in WordPress
3. Drag the widget to the relevant sidebar on the 'Widgets' page in WordPress
4. Set the StatsFC key and any other options. If you don't have a key, sign up for free at [statsfc.com](https://statsfc.com)

You can also use the `[statsfc-top-scorers]` shortcode, with the following options:

- `key` (required): Your StatsFC key
- `competition` (required*): Competition key, e.g., `EPL`
- `team` (required*): Team name, e.g., `Liverpool`
- `date` (optional): For a back-dated top scorers list, e.g., `2013-12-31`
- `limit` (optional): Maximum number of top scorers to show, e.g., `5`, `10`
- `highlight` (optional): Name of the team you want to highlight, e.g., `Liverpool`
- `default_css` (optional): Use the default widget styles, `true` or `false`

*Only one of `competition` or `team` is required.

== Frequently asked questions ==



== Screenshots ==



== Changelog ==

**1.0.1**: Fixed bug that prevents the widget from loading.

**1.0.2**: Deleted redundant images.

**1.1**: Updated team badges for 2013/14.

**1.1.1**: Use cURL to fetch API data if possible.

**1.1.2**: Fixed possible cURL bug.

**1.1.3**: Added fopen fallback if cURL request fails.

**1.1.4**: More reliable team icons.

**1.1.5**: Tweaked error message.

**1.2**: Updated to use new API.

**1.3**: Added a `date` parameter.

**1.4**: Added `[statsfc-top-scorer]` shortcode.

**1.4.2**: Updated team badges.

**1.4.3**: Default `default_css` parameter to `true`

**1.4.4**: Added badge class for each team

**1.4.5**: Use built-in WordPress HTTP API functions

**1.5**: Enabled ad-support

== Upgrade notice ==

