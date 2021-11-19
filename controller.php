<?php

namespace Concrete\Package\GeoloniaMap;

use Concrete\Core\Error\UserMessageException;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    /**
     * The minimum concrete5 version compatible with this package.
     *
     * @var string
     */
    protected $appVersionRequired = '8.5.5';

    /**
     * The handle of this package.
     *
     * @var string
     */
    protected $pkgHandle = 'geolonia_map';

    /**
     * The version number of this package.
     *
     * @var string
     */
    protected $pkgVersion = '0.0.2';

    /**
     * Get the translated name of the package.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('C5J Geolonia Map');
    }

    /**
     * Get the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t('Install a block type to embed Geolonia Map. Geolonia Maps is a web mapping platform offered by Geolonia Inc.');
    }

    /**
     * Install this package.
     *
     * @return \Concrete\Core\Entity\Package
     */
    public function install()
    {
        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            throw new UserMessageException(t('This add-on requires PHP 7.1+ to run. You are running PHP %s', PHP_VERSION));
        }

        $package = parent::install();
        $this->installContentFile('config/blocktypes.xml');

        return $package;
    }
}
