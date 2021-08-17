<?php defined('C5_EXECUTE') or die('Access Denied.');
$lang = \Concrete\Core\Localization\Localization::activeLanguage();
if ($lang !== 'ja') {
    // Geolonia Maps only support ja or en
    $lang = 'en';
}
/** @var string $location */
$location = $location ?? '';
/** @var float $latitude */
$latitude = $latitude ?? 0;
/** @var float $longitude */
$longitude = $longitude ?? 0;
/** @var int $zoom */
$zoom = $zoom ?? 16;
/** @var int $bearing */
$bearing = $bearing ?? 0;
/** @var int $pitch */
$pitch = $pitch ?? 0;
/** @var string $marker */
$marker = $marker ?? 'on';
/** @var string $marker_color */
$marker_color = $marker_color ?? '';
/** @var string $open_popup */
$open_popup = $open_popup ?? 'off';
/** @var string $popup_content */
$popup_content = $popup_content ?? '';
/** @var string $gesture_handling */
$gesture_handling = $gesture_handling ?? 'on';
/** @var string $navigation_control */
$navigation_control = $navigation_control ?? 'on';
/** @var string $geolocate_control */
$geolocate_control = $geolocate_control ?? 'off';
/** @var string $fullscreen_control */
$fullscreen_control = $fullscreen_control ?? 'off';
/** @var string $scale_control */
$scale_control = $scale_control ?? 'off';
/** @var string $style */
$style = $style ?? 'basic';
$c = \Concrete\Core\Page\Page::getCurrentPage();
if ($c->isEditMode()) {
    $loc = \Concrete\Core\Localization\Localization::getInstance();
    $loc->pushActiveContext(\Concrete\Core\Localization\Localization::CONTEXT_UI);
    ?>
    <div class="geolonia-map-container ccm-edit-mode-disabled-item">
        <div style="padding: 80px 0px 0px 0px"><?= t('Geolonia Map disabled in edit mode.') ?></div>
    </div>
    <?php
    $loc->popActiveContext();
} else { ?>
    <div
        class="geolonia geolonia-map-container"
        data-lat="<?= h($latitude) ?>"
        data-lng="<?= h($longitude) ?>"
        data-zoom="<?= h($zoom) ?>"
        data-bearing="<?= h($bearing) ?>"
        data-pitch="<?= h($pitch) ?>"
        data-marker="<?= h($marker) ?>"
        data-marker-color="<?= h($marker_color) ?>"
        data-open-popup="<?= h($open_popup) ?>"
        data-gesture-handling="<?= h($gesture_handling) ?>"
        data-navigation-control="<?= h($navigation_control) ?>"
        data-geolocate-control="<?= h($geolocate_control) ?>"
        data-fullscreen-control="<?= h($fullscreen_control) ?>"
        data-scale-control="<?= h($scale_control) ?>"
        data-style="geolonia/<?= h($style) ?>"
        data-lang="<?= h($lang) ?>"
    ><?php
        if (empty(strip_tags(nl2br($popup_content)))) {
            echo h($location);
        } else {
            echo $popup_content;
        }
        ?></div>
    <?php
}