Roundcube Webmail TaskWatermark
===============================
This plugin replaces the static watermark template used by the core with a task
aware page with tips for the user on what to do (eg. Select a message to read).

License
-------
This plugin is released under the [GNU General Public License Version 3+][gpl].

Even if skins might contain some programming work, they are not considered
as a linked part of the plugin and therefore skins DO NOT fall under the
provisions of the GPL license. See the README file located in the core skins
folder for details on the skin license.

Install
-------
* Place this plugin folder into plugins directory of Roundcube
* Add taskwatermark to $config['plugins'] in your Roundcube config

**NB:** When downloading the plugin from GitHub you will need to create a
directory called skin and place the files in there, ignoring the root
directory in the downloaded archive.

Configuration
-------------
To set the default value for the `display_first` option add the following to
your Roundcube config file:
```php
$config['display_first'] = true;
```

Customizing the Elastic skin
----------------------------
The colors and styles used by this plugin can be overridden by adding a
`_custom.less` file to the `skins/elastic` sub-folder of this plugin and
then recompiling the CSS.

Interaction with other plugins
------------------------------
The taskwatermark_show hook is triggered when listing the available actions on
the list options menu.
*Arguments:*
 * action
 * hint

*Return values:*
 * hint
 * output

[gpl]: https://www.gnu.org/licenses/gpl.html