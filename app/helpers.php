<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 5/11/2016
 * Time: 20:02
 */

function getFullLanguageFromISO639($code) {
    $iso = new \Matriphe\ISO639\ISO639();
    return $iso->languageByCode1($code);
}
