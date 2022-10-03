# Upgrade Guide

## Upgrading To 5.0 From 4.x

### Minimum PHP Version

PHP 7.2 is now the minimum required version.

### Minimum Laravel Version

Laravel 6.0 is now the minimum required version.


## Upgrading To 4.0 From 3.x

### PHP & Laravel Version Requirements

Like the latest releases of the Laravel framework, Laravel HostingPanels now requires PHP >= 7.1.3. We encourage you to upgrade to the latest versions of PHP and Laravel before upgrading to HostingPanels 4.0.

### LinkedInProvider Changes

The `LinkedInProvider` was updated to make use of the latest API version of LinkedIn. The provider will now only retrieve the most basic fields and removes the `fields` method from the provider. Please review [the related PR](https://github.com/laravel/hostingpanels/pull/310) for all details and changes.
