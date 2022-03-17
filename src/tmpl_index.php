<?php

if (!$zoomed) {

?>

<div id="tooltipBox"></div>

<div id="window">
    <div id="introText">
        <strong><a href="<?=CA_URL_ROOT?>">calculatedearth</a> shows what our planet looks like at differing sea levels.</strong>
        <p>The first few metres can help visualise the potential effects of localised flooding and perhaps global warming. Above that, it's also a good way to visualise Earth's topography.</p>
    </div>
    <form id="introForm" name="control">
        <div id="view">
            <label for="view-va">View sea level at</label> <input type="text" name="view-val" id="view-val" onkeyup="switchStream();" value="100" title="Min -100m, max 2000m">m
            <button type="button" onclick="switchStream();">View sea level &gt;</button>
        </div>
        <div id="sim">
            <label for="sim-from">From</label> <input type="text" name="sim-from" id="sim-from" value="0" title="Min -100m">m
            <label for="sim-to">to </label> <input type="text" name="sim-to" id="sim-to" value="200" title="Max 2000m">m
            <button type="button" onclick="playStream();">Run simulation &gt;</button>
        </div>
    </form>
    <div id="mapExtras">
            <a href="<?=CA_URL_ROOT?>earth/eu" style="background-image:url(<?=CA_URL_ROOT?>res/eu_shadow.jpg);" onmouseover="setTxt('tooltipBox', 'Click to zoom into Europe and interact with at varying sea-levels.'); moveObject('tooltipBox', event);">
                Europe
            </a>
            <a href="<?=CA_URL_ROOT?>earth/us" style="background-image:url(<?=CA_URL_ROOT?>res/us_shadow.jpg);" onmouseover="setTxt('tooltipBox', 'Click to zoom into North America and interact with at varying sea-levels.'); moveObject('tooltipBox', event);">
                North America
            </a>
            <a href="<?=CA_URL_ROOT?>earth/ea" style="background-image:url(<?=CA_URL_ROOT?>res/ea_shadow.jpg);" onmouseover="setTxt('tooltipBox', 'Click to zoom into East Asia and interact with at varying sea-levels.'); moveObject('tooltipBox', event);">
                East Asia
            </a>
        </div>
    <div id="containWrapper">
        <div id="contain" alt="Click a section to zoom in..." onmouseover="setTxt('tooltipBox', 'Click to zoom into area and interact with at varying sea-levels.'); moveObject('tooltipBox', event);">
            <div id="loading">
                <img src="<?=CA_URL_ROOT?>res/loading.gif" alt="Loading..." width="32" height="32">Downloading media, please wait...<span id="loadingCount"></span>
            </div>
            <img id="streamWindow" src="<?=CA_URL_ROOT?>res/globe/SeaLevel__00000m.raw.png" />

<?php

    for ($y=0; $y<5400; $y=$y+600) {
        for ($x=0; $x<10800; $x=$x+600) {
            $grid_x = round($x / 13.5);
            $grid_y = round($y / 13.5);

            if (($position_requested_x === $x) && ($position_requested_y === $y)) {
                $class = ' class="on"';
            } else {
                $class = '';
            }

            printf('<a href="%searth/%sx%s" style="left: %spx; top: %spx;"%s></a>%s', CA_URL_ROOT, $x, $y, $grid_x, $grid_y, $class, "\n");
        }
    }

?>
        </div>
    </div>
</div>

<?php

}

if ($zoomed) {
    if (($position_requested_x !== NULL) && ($position_requested_y !== NULL)) {
        $file_requested = CA_RES_SOURCE . 'bg_jpg/GlobeColBig.jpg_' . $position_requested_x . 'x' . $position_requested_y . '.jpg';
        $dir_location = CA_RES_SOURCE . $position_requested_x . 'x' . $position_requested_y;
    } elseif ($area_requested !== NULL) {
        $file_requested = CA_RES_SOURCE . 'bg_areas_jpg/' . $area_requested . '.jpg';
        $dir_location = CA_RES_SOURCE . 'areas/' . $area_requested;
    } else {
        $file_requested = null;
        $dir_location = null;
    }

    if (file_exists($file_requested)) {

?>

<div id="streamWindowCustom" title="Mouse-over / click metre levels to view sea level change...">
    <div id="streamWindowCustomMain">
        <img id="streamWindowCustomBG" src="/<?=str_replace(CA_RES_SOURCE, CA_RES_TARGET, $file_requested)?>" />
        <img id="streamWindowCustomTile" src="<?=CA_URL_ROOT?>res/sp.gif" style="border: 0; margin: 1px;" />
        <div id="loading2">
            <img src="<?=CA_URL_ROOT?>res/loading.gif" alt="Loading..." width="32" height="32" />Downloading media, please wait...
        </div>
    </div>
    <div id="streamWindowCustomControl">
        <h3><a title="Visual satellite image" alt="Visual satellite image" onmouseover="flip('<?=CA_URL_ROOT?>res/sp.gif', '0');" href="javascript:flip('<?=CA_URL_ROOT?>res/sp.gif', '0');">Sea level</a></h3>
<?php

        recursedir($dir_location, 'html');

?>
    </div>
    <div id="streamWindowCustomMiniMap">
        <img src="<?=CA_URL_ROOT?>res/globe_thumb.jpg" />
        <div id="noteData"><div id="noteLevel"></div><div id="noteLand"></div><div id="noteWater"></div></div>

<?php

        for ($y=0; $y<5400; $y=$y+600) {
            for ($x=0; $x<10800; $x=$x+600) {
                $grid_x = round($x / 40);
                $grid_y = round($y / 40);

                $class = '';
                if ($area_requested !== NULL) {
                    if ($area_requested == 'eu') {
                        if (($x >= 4800) && ($x < 6600)) {
                            if (($y >= 600) && ($y < 1800)) {
                                $class = ' class="on"';
                            }
                        }
                    }
                    if ($area_requested == 'us') {
                        if (($x >= 1200) && ($x < 3600)) {
                            if (($y >= 1200) && ($y < 2400)) {
                                $class = ' class="on"';
                            }
                        }
                    }
                    if ($area_requested == 'ea') {
                        if (($x >= 8400) && ($x < 10200)) {
                            if (($y >= 1200) && ($y < 2400)) {
                                $class = ' class="on"';
                            }
                        }
                    }
                }

                if (($position_requested_x === $x) && ($position_requested_y === $y)) {
                    $class = ' class="on"';
                }

                printf('<a href="%searth/%sx%s" style="left: %spx; top: %spx;"%s></a>%s', CA_URL_ROOT, $x, $y, $grid_x, $grid_y, $class, "\n");
            }
        }

?>

    </div>
</div>

<script>

    var customList = new Array();
    var customListPreload = new Array();
    var seaLevelData = new Array();

    function renderPage() {

<?php

recursedir($dir_location, 'js');

?>

        for (var key in customList) {
            customListPreload[customList[key]] = new Image();
            customListPreload[customList[key]].src = key;
        }
        document.getElementById('loading2').style.display = 'block';
        waitForImages();

<?php

$seaLevelData = getNumericalData($dir_location);
foreach ($seaLevelData as $seaLevel => $values) {
    print 'seaLevelData["'.$seaLevel.'"] = new Array();'."\n";
    print 'seaLevelData["'.$seaLevel.'"]["water"] = "'.$values['water'].'";'."\n";
    print 'seaLevelData["'.$seaLevel.'"]["land"] = "'.$values['land'].'";'."\n";
}

?>

    }

</script>

<?php

    }
}