<?php
defined('C5_EXECUTE') or die('Access Denied.');
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Concrete\Core\Form\Service\Widget\Color $color */
/** @var \Concrete\Core\Editor\CkeditorEditor $editor */
/** @var string $api_key */
$api_key = $api_key ?? '';
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

$is_version9 = isset($is_version9) && $is_version9 === true;
$css_class_d_none = ($is_version9 === true) ? 'd-none' : 'hidden';

echo app('helper/concrete/ui')->tabs([
    ['geolonia-map-basics', t('Basics'), true],
    ['geolonia-map-advanced', t('Advanced')],
    ['geolonia-map-preview', t('Preview')],
]); ?>
<div class="tab-content">
    <div class="<?php if ($is_version9) { ?>tab-pane show active<?php } else { ?>ccm-tab-content<?php } ?>"
         id="<?php if (!$is_version9) { ?>ccm-tab-content-<?php } ?>geolonia-map-basics" role="tabpanel">
        <fieldset>
            <div class="form-group">
                <?= $form->label('api_key', t('API Key')) ?>
                <?= $form->text('api_key', $api_key, ['placeholder' => 'YOUR-API-KEY']) ?>
                <div id="api_key_alert"
                     class="help-block"><?= t('You can get an API Key from %shere%s.', '<a href="https://app.geolonia.com/#/api-keys" target="_blank">', '</a>') ?></div>
            </div>
            <div class="form-group">
                <?= $form->label('location', t('Address')) ?>
                <div class="input-group">
                    <?= $form->text('location', $location) ?>
                    <?php if ($is_version9) { ?>
                        <?= $form->button('get-lat-lng', t('Get Coordinates'), ['class' => 'btn btn-outline-secondary']) ?>
                    <?php } else { ?>
                    <div class="input-group-btn">
                        <?= $form->button('get-lat-lng', t('Get Coordinates'), ['class' => 'btn-default']) ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <?= $form->label('latitude', t('Latitude')) ?>
                        <?= $form->number('latitude', $latitude, ['step' => 'any']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?= $form->label('longitude', t('Longitude')) ?>
                        <?= $form->number('longitude', $longitude, ['step' => 'any']) ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?= $form->label('zoom', t('Zoom')) ?>
                        <?= $form->select('zoom', range(0, 24), $zoom) ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="<?php if ($is_version9) { ?>tab-pane<?php } else { ?>ccm-tab-content<?php } ?>"
         id="<?php if (!$is_version9) { ?>ccm-tab-content-<?php } ?>geolonia-map-advanced" role="tabpanel">
        <fieldset>
            <div class="form-group">
                <?= $form->label('bearing', t('Bearing')) ?>
                <?= $form->number('bearing', $bearing, ['min' => 0, 'max' => 359]) ?>
                <p class="help-block"><?= t('Counter-clockwise angle from north. 0 - 359') ?></p>
            </div>
            <div class="form-group">
                <?= $form->label('pitch', t('Pitch')) ?>
                <?= $form->number('pitch', $pitch, ['min' => 0, 'max' => 60]) ?>
                <p class="help-block"><?= t('The initial pitch (tilt) of the map. 0 - 60') ?></p>
            </div>
            <div class="form-group">
                <?= $form->label('marker', t('Marker')) ?>
                <?= $form->select('marker', ['on' => t('On'), 'off' => t('Off')], $marker) ?>
            </div>
            <div class="form-group">
                <?= $form->label('marker_color', t('Marker Color')) ?>
                <div><?= $color->output('marker_color', $marker_color) ?></div>
            </div>
            <div class="form-group">
                <?= $form->label('open_popup', t('Open Popup by default')) ?>
                <?= $form->select('open_popup', ['on' => t('On'), 'off' => t('Off')], $open_popup) ?>
            </div>
            <div class="form-group">
                <?= $form->label('popup_content', t('Popup Content')) ?>
                <div><?= $editor->outputBlockEditModeEditor('popup_content', $popup_content) ?></div>
            </div>
            <div class="form-group">
                <?= $form->label('gesture_handling', t('Gesture Handling')) ?>
                <?= $form->select('gesture_handling', ['on' => t('On'), 'off' => t('Off')], $gesture_handling) ?>
                <p class="help-block"><?= t('Force users to use two fingers to scroll a map.') ?></p>
            </div>
            <div class="form-group">
                <?= $form->label('navigation_control', t('Navigation Control')) ?>
                <?= $form->select('navigation_control', [
                    'on' => t('On'), 'off' => t('Off'),
                    'top-right' => t('Top Right'),
                    'bottom-right' => t('Bottom Right'),
                    'bottom-left' => t('Bottom Left'),
                    'top-left' => t('Top Left'),
                ], $navigation_control) ?>
            </div>
            <div class="form-group">
                <?= $form->label('geolocate_control', t('Geolocate Control')) ?>
                <?= $form->select('geolocate_control', [
                    'on' => t('On'), 'off' => t('Off'),
                    'top-right' => t('Top Right'),
                    'bottom-right' => t('Bottom Right'),
                    'bottom-left' => t('Bottom Left'),
                    'top-left' => t('Top Left'),
                ], $geolocate_control) ?>
            </div>
            <div class="form-group">
                <?= $form->label('fullscreen_control', t('Fullscreen Control')) ?>
                <?= $form->select('fullscreen_control', [
                    'on' => t('On'), 'off' => t('Off'),
                    'top-right' => t('Top Right'),
                    'bottom-right' => t('Bottom Right'),
                    'bottom-left' => t('Bottom Left'),
                    'top-left' => t('Top Left'),
                ], $fullscreen_control) ?>
            </div>
            <div class="form-group">
                <?= $form->label('scale_control', t('Scale Control')) ?>
                <?= $form->select('scale_control', [
                    'on' => t('On'), 'off' => t('Off'),
                    'top-right' => t('Top Right'),
                    'bottom-right' => t('Bottom Right'),
                    'bottom-left' => t('Bottom Left'),
                    'top-left' => t('Top Left'),
                ], $scale_control) ?>
            </div>
            <div class="form-group">
                <?= $form->label('style', t('Style')) ?>
                <?= $form->select('style', [
                    'basic' => tc('GeoloniaMapStyleName', 'Basic'),
                    'midnight' => tc('GeoloniaMapStyleName', 'Midnight'),
                    'red-planet' => tc('GeoloniaMapStyleName', 'Red Planet'),
                    'notebook' => tc('GeoloniaMapStyleName', 'Notebook'),
                ], $style) ?>
            </div>
        </fieldset>
    </div>
    <div class="<?php if ($is_version9) { ?>tab-pane<?php } else { ?>ccm-tab-content<?php } ?>"
         id="<?php if (!$is_version9) { ?>ccm-tab-content-<?php } ?>geolonia-map-preview" role="tabpanel">
        <div class="form-group">
            <div id="preview-geolonia-map-alert" class="alert alert-warning <?= h($css_class_d_none) ?>"
                 role="alert"><?= t('You must input API Key.') ?></div>
            <div id="preview-geolonia-map-error" class="alert alert-danger <?= h($css_class_d_none) ?>"
                 role="alert"></div>
            <div id="preview-geolonia-map-container"></div>
        </div>
    </div>
</div>
<style>
    #preview-geolonia-map-container > div {
        height: 300px;
    }
</style>
<script>
$(function () {
    let elemApiKey = document.getElementById('api_key'),
        elemApiKeyAlert = document.getElementById('api_key_alert'),
        elemMapContainer = document.getElementById('preview-geolonia-map-container'),
        elemMapAlert = document.getElementById('preview-geolonia-map-alert'),
        elemMapError = document.getElementById('preview-geolonia-map-error'),
        loadMap = function () {
            let script = document.createElement('script'),
                map = document.getElementById('preview-geolonia-map')
            if (map) {
                map.remove()
            }
            if (elemApiKey.value) {
                script.type = 'text/javascript'
                script.src = 'https://cdn.geolonia.com/v1/embed?geolonia-api-key=' + elemApiKey.value
                script.onload = function () {
                    let elemMap = document.createElement('div'),
                        markerContentEditor = document.querySelector('#<?php if (!$is_version9) { ?>ccm-tab-content-<?php } ?>geolonia-map-advanced .cke_editable')
                    elemMap.id = 'preview-geolonia-map'
                    elemMap.classList.add('geolonia')
                    if (markerContentEditor.innerText !== "\n" && markerContentEditor.innerText !== "") {
                        elemMap.innerHTML = markerContentEditor.innerHTML
                    } else {
                        elemMap.innerText = document.getElementById('location').value
                    }
                    elemMap.dataset.lat = document.getElementById('latitude').value
                    elemMap.dataset.lng = document.getElementById('longitude').value
                    elemMap.dataset.zoom = document.getElementById('zoom').value
                    elemMap.dataset.bearing = document.getElementById('bearing').value
                    elemMap.dataset.pitch = document.getElementById('pitch').value
                    elemMap.dataset.marker = document.getElementById('marker').value
                    elemMap.dataset.markerColor = document.getElementsByName('marker_color')[0].value
                    elemMap.dataset.openPopup = document.getElementById('open_popup').value
                    elemMap.dataset.gestureHandling = document.getElementById('gesture_handling').value
                    elemMap.dataset.navigationControl = document.getElementById('navigation_control').value
                    elemMap.dataset.geolocateControl = document.getElementById('geolocate_control').value
                    elemMap.dataset.fullscreenControl = document.getElementById('fullscreen_control').value
                    elemMap.dataset.scaleControl = document.getElementById('scale_control').value
                    elemMap.dataset.style = 'geolonia/' + document.getElementById('style').value
                    elemMapContainer.appendChild(elemMap)
                    elemMapError.classList.add('<?= h($css_class_d_none) ?>')
                    try {
                        new geolonia.Map('#preview-geolonia-map')
                    } catch (error) {
                        elemMapError.classList.remove('<?= h($css_class_d_none) ?>')
                        elemMapError.innerText = error
                    }
                }
                document.getElementsByTagName('head')[0].appendChild(script)
                elemMapAlert.classList.add('<?= h($css_class_d_none) ?>')
            } else {
                elemMapAlert.classList.remove('<?= h($css_class_d_none) ?>')
            }
        }
    elemApiKey.addEventListener('focusout', () => {
        if (elemApiKey.value) {
            elemApiKeyAlert.classList.add('<?= h($css_class_d_none) ?>')
        } else {
            elemApiKeyAlert.classList.remove('<?= h($css_class_d_none) ?>')
        }
    })
    document.getElementById('get-lat-lng').addEventListener('click', () => {
        if (document.getElementById('location').value) {
            getLatLng(document.getElementById('location').value, (latlng) => {
                // console.log(latlng)
                document.getElementById('latitude').value = latlng.lat
                document.getElementById('longitude').value = latlng.lng
            })
        }
    })
    document.querySelector('[data-tab=geolonia-map-preview], #geolonia-map-preview-tab').addEventListener('click', () => {
        loadMap()
    })
    if (elemApiKey.value) {
        elemApiKeyAlert.classList.add('<?= h($css_class_d_none) ?>')
    }
})
</script>