<?php defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @var string $lang
 * @var string $location
 * @var float $latitude
 * @var float $longitude
 * @var int $zoom
 * @var int $bearing
 * @var int $pitch
 * @var string $marker
 * @var string $marker_color
 * @var string $open_popup
 * @var string $popup_content
 * @var string $gesture_handling
 * @var string $navigation_control
 * @var string $geolocate_control
 * @var string $fullscreen_control
 * @var string $scale_control
 * @var string $style
 */
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
} else {
    /**
     * @see https://docs.geolonia.com/embed-api/
     */
    ?>
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