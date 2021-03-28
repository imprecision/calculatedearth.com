<?php

/**
 * Scan dir recursively
 *
 * @param [type] $BASEDIR Directory
 * @param string $type Filetype filter
 * @return void Nothing (it prints directly to screen)
 */
function recursedir($BASEDIR, $type='')
{
    if (is_dir($BASEDIR)) {
        $hndl = opendir($BASEDIR);
        $list = [];
        while($file = readdir($hndl)) {
            $list[] = $file;
        }
        natcasesort($list);
        $counter = 0;

        foreach ($list as $file) {
            if ($file=='.' || $file=='..') {
                continue;
            }

            $show = false;
            $completepath = $BASEDIR.'/'.$file;
            $completepath_target = str_replace(CA_RES_SOURCE, CA_RES_TARGET, $BASEDIR) . '/' . $file;

            if (is_dir($completepath)) {

                printf("<ul>\n<b>%s</b>\n", $file);
                recursedir($completepath);
                print "</ul>\n";

            } else {
                if (preg_match("/^.*png$/i", $file)) {
                    $show = true;
                } else {
                    $show = false;
                }
                if ($show) {
                    if (preg_match("/^.*\_([0-9]{1,})m\..*$/", $file, $args)) {
                        if ($type == 'js') {
                            print "customList['". CA_URL_ROOT . $completepath_target."'] = 'opt".(int)$args[1]."m';\n";
                        } else {
                            $colour_range = [
                                '00ff00', 
                                '0cf200', 
                                '19e500', 
                                '26d800', 
                                '33cc00', 
                                '3fbf00', 
                                '4cb200', 
                                '59a500', 
                                '669900', 
                                '728c00', 
                                '7f7f00', 
                                '8c7200', 
                                '996600', 
                                'a55900', 
                                'b24c00', 
                                'bf3f00', 
                                'cc3300', 
                                'd82600', 
                                'e51900', 
                                'f20c00', 
                                'ff0000',
                                'ff0000', 
                                'f2000c', 
                                'e50019', 
                                'd80026', 
                                'cc0033', 
                                'bf003f', 
                                'b2004c', 
                                'a50059', 
                                '990066', 
                                '8c0072', 
                                '7f007f', 
                                '72008c', 
                                '660099', 
                                '5900a5', 
                                '4c00b2', 
                                '3f00bf', 
                                '3300cc', 
                                '2600d8', 
                                '1900e5', 
                                '0c00f2', 
                                '0000ff'
                            ];
                            
                            if ($counter < count($colour_range)) {
                                $c = $colour_range[$counter];
                                $counter++;
                            } else {
                                $c = $colour_range[count($colour_range)-1];
                            }
                            
                            $txt = '';
                            if (in_array((int)$args[1], [0, 5, 10, 50, 100, 150, 200, 1000, 2000, 3000])) {
                                $txt = (int)$args[1] . 'm';
                            }

                            printf('<a%s id="opt%sm" onmouseover="flip(\'/%s\', \'%s\');" href="javascript:flip(\'/%s\', \'%s\');"><div class="swccd" style="background-color: #%s;"></div>%s</a>%s', 
                                (($txt != '') ? ' style="border-top: 1px solid #' . $c . ';"' : ''), 
                                (int)$args[1], 
                                $completepath_target, 
                                (int)$args[1], 
                                $completepath, 
                                (int)$args[1], 
                                $c, 
                                $txt,
                                "\n");
                        }
                    }
                }
            }
        }
    }
}

/**
 * Get the numerical data from the TXT file
 *
 * @param [type] $dir_location Dir of file
 * @return void
 */
function getNumericalData($dir_location)
{
    $seaLevelData = array();
    if (is_dir($dir_location)) {
        $hndl = opendir($dir_location);
        while ($file = readdir($hndl)) {
            if (preg_match("/^SeaLevel-Log-.*\.txt$/", $file)) {
                $data = file_get_contents($dir_location.'/'.$file);
                $data = explode("\n", $data);
                foreach ($data as $line) {
                    $fields = explode("\t", $line);
                    if (is_numeric($fields[0])) {
                        $seaLevelData[$fields[0]]['water'] = $fields[5];
                        $seaLevelData[$fields[0]]['land'] = $fields[6];
                    }
                }
            }
        }
    }
    return $seaLevelData;
}
