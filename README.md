Roundcube Webmail Placeholder
=============================
This plugin replaces the static watermark template used by the core with a task
aware page with tips for the user on what to do (eg. Select a message to read).

ATTENTION
---------
This is just a snapshot from the GIT repository and is **NOT A STABLE version
of Placeholder**. It is Intended for use with the **GIT-master** version of
Roundcube and it may not be compatible with older versions. Stable versions of
Placeholder are available from the [Roundcube plugin repository][rcplugrepo]
(for 1.4 and above) or the [releases section][releases] of the GitHub
repository.

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
* Add placeholder to $config['plugins'] in your Roundcube config

**NB:** When downloading the plugin from GitHub you will need to create a
directory called skin and place the files in there, ignoring the root
directory in the downloaded archive.

Configuration
-------------
To set the default value for the `display_first` option add the following to
your Roundcube config file:
```php
$config['display_first'] = [true/false];
);
```

Interaction with other plugins
------------------------------
The placeholder_show hook is triggered when listing the available actions on
the list options menu.
*Arguments:*
 * action
 * hint

*Return values:*
 * hint
 * output

[rcplugrepo]: https://plugins.roundcube.net/packages/johndoh/placeholder
[releases]: https://github.com/johndoh/roundcube-placeholder/releases
[gpl]: https://www.gnu.org/licenses/gpl.html