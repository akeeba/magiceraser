Automatically remove obsolete extensions and files related to Akeeba extensions for Joomla.

### How to use

Download the file named `file_magiceraser-1.0.0.zip` below.

Go to your Joomla site's backend. On Joomla 3: go to Extensions, Install, Upload & Install. On Joomla 4: go to System, Install, Extensions, Upload Package File.

Drag the ZIP file you downloaded in the upload area.

Wait for a few seconds while the cleanup is in progress.

Ignore any warnings and the error that the file could not be installer. The latter is **expected**. Nothing is meant to be installed on your site. We are simply using Joomla's pre-installation script feature to run our custom clean-up script and then tell Joomla to abort the installation of our (fake) package. This way the Magic Eraser ()meant to clean up leftover extensions) doesn't leave any leftover extensions of its own behind!

### Things to keep in mind

**Make sure you have backed up your site before using this tool**. It is very aggressive in removing obsolete Akeeba extensions and leftovers. It won't ask you for confirmation.

**If you are using Akeeba Subscriptions 2.x to 7.x inclusive** be advised that this tool will break your installation. If you still want to use these obsolete versions of Akeeba Subscriptions you will need to reinstall the package of the version you were using _after_ using this tool. Your subscription data will NOT be touched by this tool.

### Requirements

* PHP 7.2, 7.3, 7.4, or 8.0
* Joomla 3.9, 3.10 or 4.0