<?php

namespace Concrete\Package\GeoloniaMap\Block\GeoloniaMap;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Config\Repository\Liaison;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Utility\Service\Validation\Numbers;

class Controller extends BlockController
{
    protected $btTable = 'btGeoloniaMap';
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btDefaultSet = 'multimedia';
    protected $btInterfaceWidth = '600';
    protected $btInterfaceHeight = '480';

    // Block Record
    /** @var string */
    protected $location;
    /** @var float */
    protected $latitude;
    /** @var float */
    protected $longitude;
    /** @var int */
    protected $zoom;
    /** @var int */
    protected $bearing;
    /** @var int */
    protected $pitch;
    /** @var string */
    protected $marker;
    /** @var string */
    protected $marker_color;
    /** @var string */
    protected $open_popup;
    /** @var string */
    protected $popup_content;
    /** @var string */
    protected $gesture_handling;
    /** @var string */
    protected $navigation_control;
    /** @var string */
    protected $geolocate_control;
    /** @var string */
    protected $fullscreen_control;
    /** @var string */
    protected $scale_control;
    /** @var string */
    protected $style;

    /**
     * Returns the name of the block type.
     *
     * @return string $btName
     */
    public function getBlockTypeName()
    {
        return t('Geolonia Map');
    }

    /**
     * Returns the description of the block type.
     *
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t('Embed Geolonia Maps.');
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
        $this->requireAsset('javascript', 'geolonia/community-geocoder');
        $this->set('api_key', $this->getApiKey());
        $this->set('color', $this->app->make('helper/form/color'));
        $this->set('editor', $this->app->make('editor'));
        $this->set('popup_content', $this->getPopupContentEditMode());

        $installedVersion = $this->app->make('config')->get('concrete.version_installed');
        if (version_compare($installedVersion, '9.0.0b', '>=')) {
            $this->set('is_version9', true);
        }
    }

    /**
     * Validate a block data before saving to the database.
     *
     * @param $args
     *
     * @return \Concrete\Core\Error\ErrorList\ErrorList
     */
    public function validate($args)
    {
        /** @var Numbers $numbersValidator */
        $numbersValidator = $this->app->make('helper/validation/numbers');

        $error = parent::validate($args);
        if (!$numbersValidator->number($args['latitude'], -90, 90)) {
            $error->add(t('Invalid latitude value.'));
        }
        if (!$numbersValidator->number($args['longitude'], -180, 180)) {
            $error->add(t('Invalid longitude value.'));
        }
        if (!$numbersValidator->number($args['zoom'], 0, 24)) {
            $error->add(t('Invalid zoom value.'));
        }
        if (!$numbersValidator->number($args['bearing'], 0, 359)) {
            $error->add(t('Invalid bearing value.'));
        }
        if (!$numbersValidator->number($args['pitch'], 0, 60)) {
            $error->add(t('Invalid pitch value.'));
        }
        if (!in_array($args['marker'], ['on', 'off'])) {
            $error->add(t('Invalid marker value.'));
        }
        if (!in_array($args['open_popup'], ['on', 'off'])) {
            $error->add(t('Invalid open popup value.'));
        }
        if (!in_array($args['gesture_handling'], ['on', 'off'])) {
            $error->add(t('Invalid gesture handling value.'));
        }
        if (!in_array($args['navigation_control'], ['on', 'off', 'top-right', 'bottom-right', 'bottom-left', 'top-left'])) {
            $error->add(t('Invalid navigation control value.'));
        }
        if (!in_array($args['geolocate_control'], ['on', 'off', 'top-right', 'bottom-right', 'bottom-left', 'top-left'])) {
            $error->add(t('Invalid geolocate control value.'));
        }
        if (!in_array($args['fullscreen_control'], ['on', 'off', 'top-right', 'bottom-right', 'bottom-left', 'top-left'])) {
            $error->add(t('Invalid fullscreen control value.'));
        }
        if (!in_array($args['scale_control'], ['on', 'off', 'top-right', 'bottom-right', 'bottom-left', 'top-left'])) {
            $error->add(t('Invalid scale control value.'));
        }
        if (!in_array($args['style'], ['basic', 'midnight', 'red-planet', 'notebook'])) {
            $error->add(t('Invalid style value.'));
        }

        return $error;
    }

    /**
     * Saves block data against the block's database table.
     *
     * @param array $args
     */
    public function save($args)
    {
        $args['latitude'] = (isset($args['latitude'])) ? (float) $args['latitude'] : 0;
        $args['longitude'] = (isset($args['longitude'])) ? (float) $args['longitude'] : 0;
        $args['zoom'] = (isset($args['zoom'])) ? (int) $args['zoom'] : 16;
        $args['bearing'] = (isset($args['bearing'])) ? (int) $args['bearing'] : 0;
        $args['pitch'] = (isset($args['pitch'])) ? (int) $args['pitch'] : 0;
        $args['popup_content'] = LinkAbstractor::translateTo($args['popup_content']);
        if (isset($args['api_key'])) {
            $config = $this->getPackageConfig();
            $config->save('api.key', $args['api_key']);
            unset($args['api_key']);
        }

        parent::save($args);
    }

    public function view()
    {
        $this->requireAsset('javascript', 'geolonia/embed');
        $this->set('popup_content', $this->getPopupContent());

        $lang = Localization::activeLanguage();
        if ($lang !== 'ja') {
            // Geolonia Maps only support ja or en
            $lang = 'en';
        }
        $this->set('lang', $lang);
        $this->set('location', $this->location);
        $this->set('latitude', $this->latitude);
        $this->set('longitude', $this->longitude);
        $this->set('zoom', ($this->zoom) ? $this->zoom : 16);
        $this->set('bearing', $this->bearing);
        $this->set('pitch', $this->pitch);
        $this->set('marker', ($this->marker) ? $this->marker : 'on');
        $this->set('marker_color', $this->marker_color);
        $this->set('open_popup', ($this->open_popup) ? $this->open_popup : 'off');
        $this->set('popup_content', $this->popup_content);
        $this->set('gesture_handling', ($this->gesture_handling) ? $this->gesture_handling : 'on');
        $this->set('navigation_control', ($this->navigation_control) ? $this->navigation_control : 'on');
        $this->set('geolocate_control', ($this->geolocate_control) ? $this->geolocate_control : 'off');
        $this->set('fullscreen_control', ($this->fullscreen_control) ? $this->fullscreen_control : 'off');
        $this->set('scale_control', ($this->scale_control) ? $this->scale_control : 'off');
        $this->set('style', ($this->style) ? $this->style : 'basic');
    }

    public function on_start()
    {
        $assetList = AssetList::getInstance();
        $assetList->register(
            'javascript',
            'geolonia/community-geocoder',
            'https://cdn.geolonia.com/community-geocoder.js',
            [
                'local' => false,
                'combine' => false,
                'minify' => false,
            ]
        );
        $assetList->register(
            'javascript',
            'geolonia/embed',
            'https://cdn.geolonia.com/v1/embed?geolonia-api-key=' . $this->getApiKey(),
            [
                'local' => false,
                'combine' => false,
                'minify' => false,
            ]
        );
    }

    public function getPopupContent()
    {
        return LinkAbstractor::translateFrom($this->popup_content);
    }

    public function getPopupContentEditMode()
    {
        return LinkAbstractor::translateFromEditMode($this->popup_content);
    }

    protected function getApiKey(): string
    {
        $config = $this->getPackageConfig();
        if ($config) {
            $apiKey = (string) $config->get('api.key');
        }
        if (!isset($apiKey)) {
            $apiKey = 'YOUR-API-KEY';
        }

        return $apiKey;
    }

    protected function getPackageConfig(): ?Liaison
    {
        $config = null;
        /** @var PackageService $service */
        $service = $this->app->make(PackageService::class);
        $package = $service->getClass('geolonia_map');
        if ($package) {
            $config = $package->getFileConfig();
        }

        return $config;
    }
}
