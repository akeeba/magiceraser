# Magic Eraser

Automatically remove obsolete extensions and files related to Akeeba extensions for Joomla.

## How to use

Download the latest version from [the GitHub releases page](https://github.com/akeeba/magiceraser/releases). It's a file called something like `file_magiceraser-1.0.0.zip`.

Go to your Joomla site's backend. On Joomla 3: go to Extensions, Install, Upload & Install. On Joomla 4: go to System, Install, Extensions, Upload Package File.

Drag the ZIP file you downloaded in the upload area.

Wait for a few seconds while the cleanup is in progress.

Ignore any warnings and the error that the file could not be installer. The latter is **expected**. Nothing is meant to be installed on your site. We are simply using Joomla's pre-installation script feature to run our custom clean-up script and then tell Joomla to abort the installation of our (fake) package. This way the Magic Eraser ()meant to clean up leftover extensions) doesn't leave any leftover extensions of its own behind!

## Why is this necessary

Our extensions have been around for many years, some going as far back as Joomla 1.0. Since Joomla 1.6 it's been possible to upgrade your site in place to the next Joomla version without a migration. This means that over time your site may have accumulated some leftover extensions from many years ago. This can happen despite our best efforts to _remove_ obsolete extensions when installing a new version of our software. Some reasons this can happen are:

* The obsolete extension predates Joomla's ability to uninstall obsolete extensions when installing an update.
* The uninstallation of the obsolete extension had failed in the past due to file/folder ownership or permissions.
* The uninstallation of the obsolete extension had failed in the past due to an internal Joomla error or a server timeout.
* You had installed extensions separately instead of as part of a package type extension, or your installation predates Joomla's support for package extensions.
* You restored a backup which included the obsolete extension's files and used Joomla's Discover feature to re-install it.
* The files of the extension were removed BUT the database record in the `#__extensions` table is not.

The obsolete extensions are typically ‘orphaned’ i.e. they do not belong to a package. Since update information is only provided for a package as a whole Joomla does not know if this extension is compatible with a newer version of Joomla or PHP (in fact, they are not!). When updating Joomla 3.10 or later, either to a newer point version in the same version family (e.g. 3.10.0 to 3.10.1) or to a new minor or major version (e.g. 3.10 to 4.0, 4.0 to 4.1 etc) these ‘orphaned’, obsolete extensions appear as potential upgrade issues and you are asked to disable or remove them. This is a waste of time, especially if you are trying to update dozens or hundreds of sites.

We went through all of our releases from 2011 onwards, i.e. all releases compatible with at least Joomla 1.6, and made a list of all extensions we had previously included in our software which are no longer relevant. As of August 2021 there were 159 items. Giving you this kind of massive list would also be a waste of your time. Instead, we wrote an automated script to figure out which of these 159 items is still installed on your site and remove them.

For what it's worth, we tried this on our own sites first. Our own site had a whopping 29 items still installed — though inactive — from that list. Five of these items were just database records; the files were already removed. That is to say, it doesn't matter how meticulous you are on your site administration. Leftover extensions will most definitely accumulate, like grime on your stove's extractor hood. Every so often you need to do a deep clean. Magic Eraser is the deep clean for the grime that could have come from our extensions.

You can also easily extend it to remove other extensions you _know_ should no longer be installed on your sites. This is particularly useful if you have a number of sites based on the same extensions stack — a common approach used by web agencies — that you'd like to clean up.

## Advanced use

You can tell Magic Eraser to remove _any other extension, file or folder_ from your site, on top of its built-in list.

Create the file `media/magiceraser.php` on your site with the content similar to the following:

```php
<?php
// Protect against direct access
defined('_JEXEC') or die;

$magicEraser = [
    'extensions' => [
        'com_example',
        'pkg_example',
    ],
    'files'      => [
        'media/example/foobar.php',    
    ],
    'folders'    => [
        'images/stuff_to_delete',
        'cache'    
    ],
];
?>
```

The `$magicEraser` array contains the additional stuff to remove from your site. Each of the three keys has a different meaning. The keys are explained below.

When you follow the usage instructions above the `media/magiceraser.php` file is read and its contents are merged with the built-in list before any clean up action is taken. So simple!

### `extensions` Extensions to remove

A simple string array containing the extension names to remove. Each extension follows Joomla naming conventions, except for modules which are a bit different as you'll see below. The first few letters and underscore (prefix) tell us what kind of extension we are removing. Here are the supported types:

* `com_` A component, e.g. `com_example`. This is identical to the component's folder under `components` and/or `administrator/components`.
* `plg_` A plugin, e.g. `plg_system_example`. The prefix is followed by the folder, an underscore and the plugin name. The plugin `plg_system_example` corresponds to the plugin stored in the `plugins/system/example` folder of your site.
* `mod_` A **site** module, e.g. `mod_example` which corresponds to the module stored in the `modules/mod_example` folder of your site. 
* `amod_` An **administrator** module, e.g. `amod_example` which corresponds to the module stored in the `administrator/modules/mod_example` folder of your site. 
* `pkg_` A package. The package name is something you need to get from the extensions provide and usually corresponds to how they name their ZIP file. All our (Akeeba) extension packages follow the format `pkg_something-VERSION-QUALIFIER.zip` where `pkg_something` is what you need to use, VERSION is the version number and QUALIFIER is either `core`/`pro` for extensions that have a free and paid version; OR completely missing for extensions which only have a freww version.  
* `lib_` A library package. The package name is something you need to get from the extensions provide and usually corresponds to how they name their ZIP file.
* `files_` A files package (this includes translations!). The package name is something you need to get from the extensions provide and usually corresponds to how they name their ZIP file.
* `tpl_` A template package e.g. `tpl_something` which corresponds to a template stored either in the `templates/something` folder or the `administrator/templates/something` folder.

Special cases:
* `files_fof40` will remove FOF 4.x from your site. It will remove all dependency information before uninstalling the package. If any extension is still using FOF 4 it will break. Only use if you've uninstalled all FOF 4-based extensions and can't get FOF 4 to uninstall.
* `files_fef` will remove FEF for Joomla from yoru site. It will remove all dependency information before uninstalling the package. If any extension is still using FEF it will break. Only use if you've uninstalled all FEF-based extensions and can't get FEF to uninstall.

### `files` Files to remove

List the files to remove, as paths relative to your site's root. You cannot remove files above your site's root.

### `folders` Folders (directories) to remove

List the folders to remove, as paths relative to your site's root. You cannot remove folders above your site's root.

## Built-in removal list

Magic Eraser will remove the following extensions, folders and files by default if they are present on your site.

### Obsolete extensions
* `com_cmsupdate` Akeeba CMS Update. Interim solution to add missing features to Joomla Update.
* `plg_system_akgeoip` Akeeba GeoIP provider plugin. Made obsolete by the license change of the MaxMind IP geolocation library it was using.
* `pkg_yubikey` Akeeba YubiKey authentication plugins, version 1. Superseded by Akeeba LoginGuard.
* `pkg_yubikey_plugins` Akeeba YubiKey authentication plugins, version 2. Superseded by Akeeba LoginGuard.
* `plg_system_oneclickaction` One Click Action plugin. Shipped with versions of our extensions which sent out emails to remind you of a new version being available.

### Libraries and frameworks
* `lib_f0f` F0F 2.x (F-zero-F). That was a more up-to-date version of FOF 2.x we were shipping while Joomla 3 was including a much older version of FOF 2.
* `lib_fof30` FOF 3.x, old versions
* `file_fof30` FOF 3.x, more recent versions
* `file_akeebastrapper` Akeeba Strapper. Provided Bootstrap 2.0 styling on Joomla 1.5, 1.6, 1.7 and 2.5 for our extensions.

Please note that the obsolete version of FOF 2.x shipped with Joomla 3 itself and which is found in `libraries/fof` **IS NOT** removed by Magic Eraser. This is automatically removed by Joomla itself when upgrading to Joomla 4. If you want to remove it you need to create the `media/magiceraser.php` file and add `libraries/fof` to the `folders` array.

### Obsolete folders
* `/media/akeeba_strapper` Akeeba Strapper, if the files extension is no longer in the database (safe fallback).
* `/libraries/f0f` F0F 2.x, if the library extension is no longer in the database (safe fallback).
* `/libraries/fof30` FOF 3.x, if the library or files extension is no longer in the database (safe fallback).

### Obsolete CLI Files
* `/cli/akeeba-update.php` Akeeba Backup automatic updates script. We no longer support unattended updates (too risky).
* `/cli/admintools-update.php` Admin Tools automatic updates script. We no longer support unattended updates (too risky).
* `/cli/ats-upgrade.php` Akeeba Ticket System automatic updates script. We no longer support unattended updates (too risky).
* `/cli/docimport-upgrade.php` DocImport automatic updates script. We no longer support unattended updates (too risky).
* `/cli/ars-update.php` Akeeba Release System automatic updates script. We no longer support unattended updates (too risky).
* `/cli/cmsupdate.php` Akeeba CMS Update script to update Joomla automatically. The extension is discontinued.
* `/cli/admintools-dbrepair.php` Admin Tools, Database Repair script. This feature is now part of the Admin Tools system plugin.
* `/cli/docimport-update.php` DocImport categories update script. This feature was removed due to routing issues in cross-article links that have to do with how Joomla itself works under the hood.

### Obsolete extensions formerly bundled with Akeeba Backup
* `amod_akadmin` Backup status (admin module). This was from before we contributed support for quickicon plugins in Joomla 1.7.
* `plg_jmonitoring_akeebabackup` Integration with the JMonitoring component; the third party component is no longer available.
* `plg_system_akeebaupdatecheck` Akeeba Backup update check plugin. We no longer support sending update information by email; there are many services which do that and more.
* `plg_system_aklazy` Lazy backup scheduling plugin. It was unreliable on bigger or rarely visited sites, thus removed a long time ago.
* `plg_system_srp` System Restore Points plugin. It was no longer reliable after the changes made in Joomla 1.6 regarding extensions handling and was remove a long time ago.

### Obsolete extensions formerly bundled with Admin Tools
* `amod_atjupgrade` Joomla update status admin module. We contributed this feature to Joomla itself.
* `plg_quickicon_atoolsjupdatecheck` Joomla update status quickicon module. We contributed this feature to Joomla itself.
* `plg_system_atoolsjupdatecheck` Admin Tools Joomla update check plugin. We contributed this feature to Joomla itself.
* `plg_system_atoolsupdatecheck` Admin Tools update check plugin. We no longer support sending update information by email; there are many services which do that and more.
* `plg_system_admintoolsactionlog` Obsolete version of the Admin Tools integration with Joomla User Actions Log. Replaced with a proper actionlog plugin.

### Obsolete extensions formerly bundled with Akeeba Ticket System
* `plg_ats_alphauserpoints` Alpha User Points integration; the third party extension is no longer maintained.
* `plg_ats_akeebasubs` Integration with Akeeba Subscriptions; the Akeeba Subscriptions extension is no longer maintained.
* `plg_ats_akeebasubslegacy` Integration with Akeeba Subscriptions, older versions; the Akeeba Subscriptions extension is no longer maintained.

### Obsolete extensions formerly bundled with DocImport
* `plg_sh404sefextplugins_com_docimport` sh404SEF integration plugin. We only support core Joomla routing.
* `mod_docimport_search` Custom search module (Universal Search). We discotninued this feature.

### Obsolete extensions formerly bundled with Akeeba Release System
* `plg_ars_bleedingedgediff` Bleeding Edge diff plugin. This feature has been removed.
* `plg_ars_bleedingedgematurity` Bleeding Edge maturity plugin. This feature has been removed.
* `plg_ars_tainting` ZIP tainting plugin (not publicly released, available on GitHub). This feature has been removed.
* `plg_sh404sefextplugins_com_ars` sh404SEF integration with Akeeba Release System. We only support core Joomla routing.
* `file_ars` Automatic Akeeba Release System update CLI scripts. We no longer support unattended updates (too risky).
* `mod_arsdlid` Download ID module. Made obsolete by the Download ID content plugin and the fact that Custom modules can use said content plugin.
* `plg_system_arsjed` System plugin for Joomla Extensions Directory automatic extension update integration. JED never launched this feature.

### Obsolete extensions formerly bundled with Akeeba Subscriptions

Please note that Akeeba Subscriptions is End of Life. Magic Eraser removes all modules and plugins included in Akeeba Subscriptions 1.x through 7.x, but not those included in Akeeba Subscriptions 8.x. This allows you to uninstall the Akeeba Subscriptions package cleanly.

If you are still using an older version of Akeeba Subscriptions please make sure that you have an installable ZIP file for that version available. After running Magic Eraser you will need to reinstall that ZIP file. Again, please note that Akeeba Subscriptions is End of Life and the old versions are not fully compatible with Joomla 3.9/3.10 and are definitely NOT compatible with Joomla 4.

* `amod_akeebasubs`
* `mod_aktaxcountry`
* `plg_akeebasubs_aceshop`
* `plg_akeebasubs_acymailing`
* `plg_akeebasubs_adminemails`
* `plg_akeebasubs_affemails`
* `plg_akeebasubs_ageverification`
* `plg_akeebasubs_agora`
* `plg_akeebasubs_agreetoeu`
* `plg_akeebasubs_agreetotos`
* `plg_akeebasubs_atscreditslegacy`
* `plg_akeebasubs_autocity`
* `plg_akeebasubs_canalyticscommerce`
* `plg_akeebasubs_cb`
* `plg_akeebasubs_cbsync`
* `plg_akeebasubs_ccinvoices`
* `plg_akeebasubs_communityacl`
* `plg_akeebasubs_constantcontact`
* `plg_akeebasubs_customfields`
* `plg_akeebasubs_docman`
* `plg_akeebasubs_easydiscuss`
* `plg_akeebasubs_freshbooks`
* `plg_akeebasubs_frontenduseraccess`
* `plg_akeebasubs_gacommerce`
* `plg_akeebasubs_invoices`
* `plg_akeebasubs_iplogger`
* `plg_akeebasubs_iproperty`
* `plg_akeebasubs_jce`
* `plg_akeebasubs_jomsocial`
* `plg_akeebasubs_joomlaprofilesync`
* `plg_akeebasubs_juga`
* `plg_akeebasubs_jxjomsocial`
* `plg_akeebasubs_k2`
* `plg_akeebasubs_kunena`
* `plg_akeebasubs_mailchimp`
* `plg_akeebasubs_mijoshop`
* `plg_akeebasubs_needslogout`
* `plg_akeebasubs_ninjaboard`
* `plg_akeebasubs_phocadownload`
* `plg_akeebasubs_projectfork`
* `plg_akeebasubs_projectfork4`
* `plg_akeebasubs_recaptcha`
* `plg_akeebasubs_redshop`
* `plg_akeebasubs_redshopusersync`
* `plg_akeebasubs_reseller`
* `plg_akeebasubs_samplefields`
* `plg_akeebasubs_slavesubs`
* `plg_akeebasubs_sql`
* `plg_akeebasubs_subscriptionemailsdebug`
* `plg_akeebasubs_tienda`
* `plg_akeebasubs_tracktime`
* `plg_akeebasubs_userdelete`
* `plg_akeebasubs_vm`
* `plg_akeebasubs_vm2`
* `plg_akeebasubs_zohoinvoice`
* `plg_akpayment_2checkout`
* `plg_akpayment_2conew`
* `plg_akpayment_allopass`
* `plg_akpayment_alphauserpoints`
* `plg_akpayment_authorizenet`
* `plg_akpayment_be2bill`
* `plg_akpayment_beanstream`
* `plg_akpayment_braintree`
* `plg_akpayment_cardstream`
* `plg_akpayment_cashu`
* `plg_akpayment_ccavenue`
* `plg_akpayment_clickandbuy`
* `plg_akpayment_cmcic`
* `plg_akpayment_deltapay`
* `plg_akpayment_dwolla`
* `plg_akpayment_epaydk`
* `plg_akpayment_eselectplus`
* `plg_akpayment_eway`
* `plg_akpayment_ewayrapid3`
* `plg_akpayment_exact`
* `plg_akpayment_gocardless`
* `plg_akpayment_googlecheckout`
* `plg_akpayment_ifthen`
* `plg_akpayment_mercadopago`
* `plg_akpayment_mobilpaycc`
* `plg_akpayment_mobilpaysms`
* `plg_akpayment_moip`
* `plg_akpayment_moipassinaturas`
* `plg_akpayment_moneris`
* `plg_akpayment_nochex`
* `plg_akpayment_none`
* `plg_akpayment_offline`
* `plg_akpayment_pagseguro`
* `plg_akpayment_payfast`
* `plg_akpayment_paymill`
* `plg_akpayment_paymilldss3`
* `plg_akpayment_paypal`
* `plg_akpayment_paypalpaymentspro`
* `plg_akpayment_paypalproexpress`
* `plg_akpayment_paypoint`
* `plg_akpayment_paysafe`
* `plg_akpayment_payu`
* `plg_akpayment_postfinancech`
* `plg_akpayment_przelewy24`
* `plg_akpayment_rbkmoney`
* `plg_akpayment_realex`
* `plg_akpayment_robokassa`
* `plg_akpayment_saferpay`
* `plg_akpayment_sagepay`
* `plg_akpayment_scnet`
* `plg_akpayment_scnetintegrated`
* `plg_akpayment_skrill`
* `plg_akpayment_stripe`
* `plg_akpayment_suomenverkkomaksut`
* `plg_akpayment_upay`
* `plg_akpayment_verotel`
* `plg_akpayment_viva`
* `plg_akpayment_wepay`
* `plg_akpayment_worldpay`
* `plg_akpayment_zarinpal`
* `plg_ccinvoicetags_akeebasubs`
* `plg_sh404sefextplugins_com_akeebasubs`
* `plg_system_as2cocollation`
* `plg_system_affiliatesessiongeneration`
* `plg_system_aslogoutuser`
* `plg_system_aspaypalcollation`
* `plg_system_idevaffiliate`
* `plg_system_postaffiliatepro`
* `plg_user_aslogoutuser`
* `plg_user_asresetform`

### Obsolete extensions formerly bundled with Akeeba YubiKey Authentication Plugins

These should be removed when removing the package. We try to uninstall the individual extensions included in the package as a safe fallback.

* `plg_user_yubikey`
* `plg_authentication_yubikey`
* `plg_twofactorauth_yubikeytotp`
* `plg_twofactorauth_yubikeyplus`
* `plg_twofactorauth_u2f`

### Obsolete extensions formerly bundled with Akeeba CMS Update

These should be removed when removing the Akeeba CMS Update component. We try to uninstall the individual extensions included with the component as a safe fallback.

* `plg_system_cmsupdateemail` Plugin to send Joomla update availability emails
* `plg_quickicon_cmsupdate` Quickicon plugin to show Joomla update status