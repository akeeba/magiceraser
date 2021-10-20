<?php
/*
 * @package   MagicEraser
 * @copyright Copyright (c)2021-2021 Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') || die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Installer as JoomlaInstaller;
use Joomla\CMS\Log\Log;

/**
 * Magic Eraser installer script.
 *
 * This script does performs all the extension removal magic
 */
class file_magiceraserInstallerScript
{
	/**
	 * Obsolete extensions to remove
	 *
	 * Internal Joomla name of the obsolete extensions to remove. Note that admin modules are noted as `amod_` instead
	 * of `mod_` to make it easier for us to manage things.
	 *
	 * @var string[]
	 */
	private $obsoleteExtensions = [
		// Obsolete extensions
		'com_cmsupdate',
		'plg_system_akgeoip',
		'pkg_yubikey',
		'pkg_yubikey_plugins',
		'plg_system_oneclickaction',
		'pkg_compliance',

		// Libraries and frameworks
		'lib_f0f#prefix',
		'lib_fof30',
		'file_fof30',
		'file_akeebastrapper',
		'file_strapper',
		'files_strapper',
		'file_strapper30',

		// Obsolete extensions formerly bundled with Akeeba Backup
		'amod_akadmin',
		'plg_jmonitoring_akeebabackup',
		'plg_system_akeebaupdatecheck',
		'plg_system_aklazy',
		'plg_system_srp',

		// Obsolete extensions formerly bundled with Admin Tools
		'amod_atjupgrade',
		'plg_quickicon_atoolsjupdatecheck',
		'plg_system_atoolsjupdatecheck',
		'plg_system_atoolsupdatecheck',
		'plg_system_admintoolsactionlog',

		// Obsolete extensions formerly bundled with Akeeba Ticket System
		'plg_ats_alphauserpoints',
		'plg_ats_akeebasubs',
		'plg_ats_akeebasubslegacy',

		// Obsolete extensions formerly bundled with DocImport
		'plg_sh404sefextplugins_com_docimport',
		'mod_docimport_search',

		// Obsolete extensions formerly bundled with Akeeba Release System
		'plg_ars_bleedingedgediff',
		'plg_ars_bleedingedgematurity',
		'plg_ars_tainting',
		'plg_sh404sefextplugins_com_ars',
		'file_ars',
		'files_ars',
		'mod_arsdlid',
		'plg_system_arsjed',

		// Obsolete extensions formerly bundled with Akeeba Subscriptions
		'amod_akeebasubs',
		'mod_aktaxcountry',
		'plg_akeebasubs_aceshop',
		'plg_akeebasubs_acymailing',
		'plg_akeebasubs_adminemails',
		'plg_akeebasubs_affemails',
		'plg_akeebasubs_ageverification',
		'plg_akeebasubs_agora',
		'plg_akeebasubs_agreetoeu',
		'plg_akeebasubs_agreetotos',
		'plg_akeebasubs_atscreditslegacy',
		'plg_akeebasubs_autocity',
		'plg_akeebasubs_canalyticscommerce',
		'plg_akeebasubs_cb',
		'plg_akeebasubs_cbsync',
		'plg_akeebasubs_ccinvoices',
		'plg_akeebasubs_communityacl',
		'plg_akeebasubs_constantcontact',
		'plg_akeebasubs_customfields',
		'plg_akeebasubs_docman',
		'plg_akeebasubs_easydiscuss',
		'plg_akeebasubs_freshbooks',
		'plg_akeebasubs_frontenduseraccess',
		'plg_akeebasubs_gacommerce',
		'plg_akeebasubs_invoices',
		'plg_akeebasubs_iplogger',
		'plg_akeebasubs_iproperty',
		'plg_akeebasubs_jce',
		'plg_akeebasubs_jomsocial',
		'plg_akeebasubs_joomlaprofilesync',
		'plg_akeebasubs_juga',
		'plg_akeebasubs_jxjomsocial',
		'plg_akeebasubs_k2',
		'plg_akeebasubs_kunena',
		'plg_akeebasubs_mailchimp',
		'plg_akeebasubs_mijoshop',
		'plg_akeebasubs_needslogout',
		'plg_akeebasubs_ninjaboard',
		'plg_akeebasubs_phocadownload',
		'plg_akeebasubs_projectfork',
		'plg_akeebasubs_projectfork4',
		'plg_akeebasubs_recaptcha',
		'plg_akeebasubs_redshop',
		'plg_akeebasubs_redshopusersync',
		'plg_akeebasubs_reseller',
		'plg_akeebasubs_samplefields',
		'plg_akeebasubs_slavesubs',
		'plg_akeebasubs_sql',
		'plg_akeebasubs_subscriptionemailsdebug',
		'plg_akeebasubs_tienda',
		'plg_akeebasubs_tracktime',
		'plg_akeebasubs_userdelete',
		'plg_akeebasubs_vm',
		'plg_akeebasubs_vm2',
		'plg_akeebasubs_zohoinvoice',
		'plg_akpayment_2checkout',
		'plg_akpayment_2conew',
		'plg_akpayment_allopass',
		'plg_akpayment_alphauserpoints',
		'plg_akpayment_authorizenet',
		'plg_akpayment_be2bill',
		'plg_akpayment_beanstream',
		'plg_akpayment_braintree',
		'plg_akpayment_cardstream',
		'plg_akpayment_cashu',
		'plg_akpayment_ccavenue',
		'plg_akpayment_clickandbuy',
		'plg_akpayment_cmcic',
		'plg_akpayment_deltapay',
		'plg_akpayment_dwolla',
		'plg_akpayment_epaydk',
		'plg_akpayment_eselectplus',
		'plg_akpayment_eway',
		'plg_akpayment_ewayrapid3',
		'plg_akpayment_exact',
		'plg_akpayment_gocardless',
		'plg_akpayment_googlecheckout',
		'plg_akpayment_ifthen',
		'plg_akpayment_mercadopago',
		'plg_akpayment_mobilpaycc',
		'plg_akpayment_mobilpaysms',
		'plg_akpayment_moip',
		'plg_akpayment_moipassinaturas',
		'plg_akpayment_moneris',
		'plg_akpayment_nochex',
		'plg_akpayment_none',
		'plg_akpayment_offline',
		'plg_akpayment_pagseguro',
		'plg_akpayment_payfast',
		'plg_akpayment_paymill',
		'plg_akpayment_paymilldss3',
		'plg_akpayment_paypal',
		'plg_akpayment_paypalpaymentspro',
		'plg_akpayment_paypalproexpress',
		'plg_akpayment_paypoint',
		'plg_akpayment_paysafe',
		'plg_akpayment_payu',
		'plg_akpayment_postfinancech',
		'plg_akpayment_przelewy24',
		'plg_akpayment_rbkmoney',
		'plg_akpayment_realex',
		'plg_akpayment_robokassa',
		'plg_akpayment_saferpay',
		'plg_akpayment_sagepay',
		'plg_akpayment_scnet',
		'plg_akpayment_scnetintegrated',
		'plg_akpayment_skrill',
		'plg_akpayment_stripe',
		'plg_akpayment_suomenverkkomaksut',
		'plg_akpayment_upay',
		'plg_akpayment_verotel',
		'plg_akpayment_viva',
		'plg_akpayment_wepay',
		'plg_akpayment_worldpay',
		'plg_akpayment_zarinpal',
		'plg_ccinvoicetags_akeebasubs',
		'plg_sh404sefextplugins_com_akeebasubs',
		'plg_system_as2cocollation',
		'plg_system_affiliatesessiongeneration',
		'plg_system_aslogoutuser',
		'plg_system_aspaypalcollation',
		'plg_system_idevaffiliate',
		'plg_system_postaffiliatepro',
		'plg_user_aslogoutuser',
		'plg_user_asresetform',

		// Obsolete extensions formerly bundled with Akeeba YubiKey Authentication Plugins
		'plg_user_yubikey',
		'plg_authentication_yubikey',
		'plg_twofactorauth_yubikeytotp',
		'plg_twofactorauth_yubikeyplus',
		'plg_twofactorauth_u2f',

		// Obsolete extensions formerly bundled with Akeeba CMS Update
		'plg_system_cmsupdateemail',
		'plg_quickicon_cmsupdate',
	];

	/**
	 * Obsolete files to remove, relative to site's root
	 *
	 * @var string[]
	 */
	private $obsoleteFiles = [
		'cli/akeeba-update.php',
		'cli/admintools-update.php',
		'cli/ats-upgrade.php',
		'cli/docimport-upgrade.php',
		'cli/ars-update.php',
		'cli/cmsupdate.php',
		'cli/admintools-dbrepair.php',
		'cli/docimport-update.php',
	];

	/**
	 * Obsolete folders to remove, relative to site's root
	 *
	 * @var string[]
	 */
	private $obsoleteFolders = [
		'media/akeeba_strapper',
		'libraries/f0f',
		'libraries/fof30',
	];

	/**
	 * Executes before installing this (fake) package.
	 *
	 * We hook into this event to remove all the cruft, then pretend we cannot be installed. Zero clean up!
	 *
	 * @param $type
	 * @param $parent
	 *
	 * @return bool|void
	 */
	public function preflight($type, $parent)
	{
		// Do not run on uninstall.
		if ($type === 'uninstall')
		{
			return true;
		}

		/**
		 * Import additional configuration from the file media/magiceraser.php
		 * The file must define the following array:
		 *
		 * $magicEraser = [
		 *  'extensions' => [ .... ], // added to $this->obsoleteExtensions
		 *  'files'      => [ .... ], // added to $this->files
		 *  'folders'    => [ .... ], // added to $this->folders
		 * ];
		 *
		 * Each folder must follow the format of the respective folder defined in this class.
		 *
		 * We use this file for the migration of our own site.
		 *
		 * If you include file_fof40 in 'extensions' FOF 4 will also be uninstalled (and its dependencies removed).
		 * If you include file_fef in 'extensions' FEF will also be uninstalled (and its dependencies removed).
		 */
		$this->importAdditionalManifest(JPATH_SITE . '/media/magiceraser.php');

		// Get ready to log the actions taken
		$actions = [
			'extensions' => [],
			'files'      => [],
			'folders'    => [],
		];

		// Remove Akeeba Strapper and FOF 3 dependencies. This allows these packages to be uninstalled.
		$this->removeDependencyInformation();

		// Uninstall extensions
		foreach ($this->obsoleteExtensions as $obsoleteExtension)
		{
			if ($this->uninstallExtension($obsoleteExtension))
			{
				$actions['extensions'][] = $obsoleteExtension;
			}
		}

		// Remove folders
		foreach ($this->obsoleteFolders as $folder)
		{
			$realFolder = JPATH_SITE . '/' . $folder;
			@clearstatcache($realFolder);

			if (@file_exists($realFolder) && @is_dir($realFolder) && Folder::delete($realFolder))
			{
				$actions['folders'][] = $folder;
			}
		}

		// Remove files
		foreach ($this->obsoleteFiles as $file)
		{
			$realFile = JPATH_SITE . '/' . $file;
			@clearstatcache($realFile);

			if (@file_exists($realFile) && @is_dir($realFile) && Folder::delete($realFile))
			{
				$actions['files'][] = $realFile;
			}
		}

		// Sum it all up for the user.
		$heading = <<< HTML
<h3>Akeeba Magic Eraser</h3>
<p>Automatically removes obsolete extensions and files related to Akeeba extensions for Joomla</p>
HTML;


		$numExtensions = count($actions['extensions']);
		$numFiles      = count($actions['files']);
		$numFolders    = count($actions['folders']);

		if (empty($numExtensions) && empty($numFiles) && empty($numFolders))
		{
			$alertType = 'success';
			$body      = <<< HTML
<h4>No action necessary</h4>
<p>
	There are no leftovers to be removed by this script.
</p>
HTML;
		}
		else
		{
			$alertType = 'info';
			$body      = '';

			if ($numExtensions)
			{
				$body .= sprintf('<p>I have uninstalled %d obsolete extensions:</p><ul>', $numExtensions);

				foreach ($actions['extensions'] as $item)
				{
					$body .= "\n<li>$item</li>";
				}

				$body .= "\n</ul>\n";
			}

			if ($numFolders)
			{
				$body .= sprintf('<p>I have removed %d obsolete folders:</p><ul>', $numFolders);

				foreach ($actions['folders'] as $item)
				{
					$body .= "\n<li>$item</li>";
				}

				$body .= "\n</ul>\n";
			}

			if ($numFiles)
			{
				$body .= sprintf('<p>I have removed %d obsolete files:</p><ul>', $numFiles);

				foreach ($actions['files'] as $item)
				{
					$body .= "\n<li>$item</li>";
				}

				$body .= "\n</ul>\n";
			}

		}

		$notice = <<< HTML
<div class="alert alert-$alertType">
$heading
$body
<hr/>
<p>
	Please ignore any Joomla notices (stuff over yellow background) you see BEFORE or AFTER this here message, including the “Extension Install: Custom install routine failure” mesage. They are to be expected when removing leftovers.
</p>
<p>
	Please also ignore the error message “Error installing file” AFTER this here message. This is also expected. Magic Eraser is actually not an installable extension. It's just a cleanup script wrapepd inside a Joomla extension package. After the script has finished running we tell Joomla to abort the installation attempt. This causes this error to appear WITHOUT causing any problems to your site. In fact, it's just a smart way to clean up obsolete extensions <em>without</em> having you install yet another extension that needs to be immediately uninstalled. Less work for you!
</p>
</div>
HTML;

		// Show notice
		echo $notice;
		$this->log($notice);

		// Fail the “installation”. LOL!
		return false;
	}

	protected function log(string $message, bool $didUninstall = false, string $category = 'jerror'): void
	{
		// Just in case...
		if (!class_exists('\Joomla\CMS\Log\Log', true))
		{
			return;
		}

		try
		{
			Log::add($message, Log::INFO, $category);
		}
		catch (Exception $e)
		{
			// Swallow the exception.
		}
	}

	private function removeDependencyInformation()
	{
		// FOF 3.x and Strapper 3.x dependency keys are always removed
		$removeCommonKeys = ['fof30', 'strapper30'];

		// If you're removing FOF 4.x I need to also remove its dependencies.
		if (in_array('file_fof40', $this->obsoleteExtensions))
		{
			$removeCommonKeys[] = 'fof40';
		}

		// If you're removing FEF I need to also remove its dependencies.
		if (in_array('file_fef', $this->obsoleteExtensions))
		{
			$removeCommonKeys[] = 'file_fef';
		}

		$db       = Factory::getDbo();
		$query    = $db->getQuery(true)
			->delete($db->quoteName('#__akeeba_common'))
			->where($db->quoteName('key') . 'IN(' . implode(', ', array_map([$db, 'quote'], $removeCommonKeys)) . ')');
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $e)
		{
			// No problem if this fails.
		}
	}

	/**
	 * Returns the extension ID for a Joomla extension given its name.
	 *
	 * This is deliberately public so that custom handlers can use it without having to reimplement it.
	 *
	 * @param   string  $extension  The extension name, e.g. `plg_system_example`.
	 *
	 * @return  int|null  The extension ID or null if no such extension exists
	 */
	private function getExtensionId(string $extension): ?int
	{
		if (isset($this->extensionIds[$extension]))
		{
			return $this->extensionIds[$extension];
		}

		$this->extensionIds[$extension] = null;

		$criteria = $this->extensionNameToCriteria($extension);

		if (empty($criteria))
		{
			return $this->extensionIds[$extension];
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('extension_id'))
			->from($db->quoteName('#__extensions'));

		foreach ($criteria as $key => $value)
		{
			$query->where($db->qn($key) . ' = ' . $db->quote($value));
		}

		try
		{
			$this->extensionIds[$extension] = (int) $db->setQuery($query)->loadResult();
		}
		catch (RuntimeException $e)
		{
			return null;
		}

		return $this->extensionIds[$extension];
	}

	/**
	 * Convert a Joomla extension name to `#__extensions` table query criteria.
	 *
	 * The following kinds of extensions are supported:
	 * * `pkg_something` Package type extension
	 * * `com_something` Component
	 * * `plg_folder_something` Plugins
	 * * `mod_something` Site modules
	 * * `amod_something` Administrator modules. THIS IS CUSTOM.
	 * * `file_something` File type extension
	 * * `lib_something` Library type extension
	 *
	 * @param   string  $extensionName
	 *
	 * @return  array{type: string, element: string, folder: string, client_id: string}
	 */
	private function extensionNameToCriteria(string $extensionName): array
	{
		$parts = explode('_', $extensionName, 3);

		switch ($parts[0])
		{
			case 'pkg':
				return [
					'type'    => 'package',
					'element' => $extensionName,
				];

			case 'com':
				return [
					'type'    => 'component',
					'element' => $extensionName,
				];

			case 'plg':
				return [
					'type'    => 'plugin',
					'folder'  => $parts[1],
					'element' => $parts[2],
				];

			case 'mod':
				return [
					'type'      => 'module',
					'element'   => $extensionName,
					'client_id' => 0,
				];

			// That's how we note admin modules
			case 'amod':
				return [
					'type'      => 'module',
					'element'   => substr($extensionName, 1),
					'client_id' => 1,
				];

			case 'file':
			case 'files':
				return [
					'type'    => 'file',
					'element' => $extensionName,
				];

			case 'lib':
				$element = substr($extensionName, 4);

				if (substr($element, -7) === '#prefix')
				{
					$element = 'lib_' . substr($element, 0, -7);
				}

				return [
					'type'        => 'library',
					'element'     => $element,
				];

			case 'tpl':
				return [
					'type'    => 'template',
					'element' => substr($extensionName, 4),
				];
		}

		return [];
	}

	private function uninstallExtension(string $obsoleteExtension)
	{
		$info = $this->extensionNameToCriteria($obsoleteExtension);
		$eid  = $this->getExtensionId($obsoleteExtension);

		if (empty($info) || empty($eid))
		{
			return false;
		}

		$installer = new JoomlaInstaller;
		$result    = $installer->uninstall($info['type'], $eid);

		if ($result)
		{
			return true;
		}

		if ($this->checkPresence($info))
		{
			return false;
		}

		// If the extension but the record is then just remove the entry!
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__extensions'))
			->where($db->quoteName('extension_id') . ' = ' . $db->quote($eid));
		try
		{
			$db->setQuery($query)->execute();

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private function checkPresence(array $info)
	{
		$files   = [];
		$folders = [];

		switch ($info['type'])
		{
			case 'package':
				$files = [
					JPATH_MANIFESTS . '/packages/' . $info['element'] . '.xml',
				];
				break;

			case 'file':
				$files = [
					JPATH_MANIFESTS . '/files/' . $info['element'] . '.xml',
				];
				break;

			case 'library':
				$files = [
					JPATH_MANIFESTS . '/libraries/' . $info['element'] . '.xml',
				];
				break;

			case 'component':
				$folders = [
					JPATH_SITE . '/components/' . $info['element'],
					JPATH_ADMINISTRATOR . '/components/' . $info['element'],
					JPATH_SITE . '/media/' . $info['element'],
					JPATH_SITE . '/api/components/' . $info['element'],
				];
				break;

			case 'plugin':
				$folders = [
					JPATH_PLUGINS . '/' . $info['folder'] . '/' . $info['element'],
				];
				$files   = [
					JPATH_PLUGINS . '/' . $info['folder'] . '/' . $info['element'] . '.php',
				];
				break;

			case 'module':
				$clientId   = $info['client_id'] ?? 0;
				$rootFolder = ($clientId == 0) ? JPATH_SITE : JPATH_ADMINISTRATOR;
				$folders    = [
					$rootFolder . '/modules/' . $info['element'],
				];
				break;
		}

		foreach ($files as $file)
		{
			clearstatcache($file);

			if (@file_exists($file) && @is_file($file))
			{
				return true;
			}
		}

		foreach ($folders as $folder)
		{
			clearstatcache($folder);

			if (@file_exists($folder) && @is_dir($folder))
			{
				return true;
			}
		}

		return false;
	}

	private function importAdditionalManifest($targetFile)
	{
		@clearstatcache($targetFile);

		if (!file_exists($targetFile) || !is_file($targetFile))
		{
			return;
		}

		@include $targetFile;

		if (!isset($magicEraser) || !is_array($magicEraser))
		{
			return;
		}

		$this->obsoleteExtensions = array_unique(array_merge($this->obsoleteExtensions, $magicEraser['extensions'] ?? []));
		$this->obsoleteFolders    = array_unique(array_merge($this->obsoleteFolders, $magicEraser['folders'] ?? []));
		$this->obsoleteFiles      = array_unique(array_merge($this->obsoleteFiles, $magicEraser['files'] ?? []));
	}

}