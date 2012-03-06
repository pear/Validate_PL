<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in Poland
 *
 * PHP Versions 4 and 5
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_PL
 * @author    Piotr Klaban <makler@man.torun.pl>
 * @author    Pawel Olejniczak <pawel.olejniczak@gmail.com>
 * @copyright 1997-2005 Piotr Klaban
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_PL
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Poland
 *
 * This class provides methods to validate:
 *  - NIP (Polish tax identification number)
 *  - Bank account number
 *  - PESEL (Polish human identification number)
 *  - REGON (Polish statistical national economy register)
 *
 * @category  Validate
 * @package   Validate_PL
 * @author    Piotr Klaban <makler@man.torun.pl>
 * @copyright 1997-2005 Piotr Klaban
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_PL
 */
class Validate_PL
{
    /**
     * Validates NIP (Polish tax identification number)
     *
     * Sprawdza NIP (Numer Identyfikacji Podatkowej)
     * http://chemeng.p.lodz.pl/zylla/ut/nip-rego.html
     *
     * @param string $nip 9-digit number to validate
     *
     * @return bool
     */
    function nip($nip)
    {
        static $weights_nip = array(6,5,7,2,3,4,5,6,7);

        // remove any dashes, spaces, returns, tabs or slashes
        $nip = str_replace(array('-','/',' ',"\t","\n"), '', $nip);

        // check if this is a 10-digit number
        if (!is_numeric($nip) || strlen($nip) != 10) {
            return false;
        }

        // check control sum
        return Validate::_checkControlNumber($nip, $weights_nip, 11);
    }

    /**
     * Validates bank number (Polish banks)
     *
     * @param string $number 8-digit number to validate
     *
     * @return bool
     */
    function bankBranch($number)
    {
        static $weights_bank_branch = array(7,1,3,9,7,11,3);

        // remove any dashes, spaces, returns, tabs or slashes
        $number = str_replace(array('-','/',' ',"\t","\n"), '', $number);

        // check if this is a 8-digit number
        if (!is_numeric($number) || strlen($number) != 8) {
            return false;
        }

        // check control sum
        return Validate::_checkControlNumber($number, $weights_bank_branch, 10);
    }

    /**
     * Validates PESEL (Polish human identification number)
     *
     * Sprawdza PESEL (Powszechny Elektroniczny System Ewidencji Ludnoci)
     * http://www.mswia.gov.pl/crp_pesel.html
     * NOTE: some people can have the same PESEL, and some can have
     * PESEL with wrong numbers in place of birth date.
     *
     * @param string $pesel  11-digit number to validate
     * @param array  &$birth reference to array - returns birth date and sex
     *               (either 'female' or 'male' string) extracted from pesel
     *
     * @return bool
     */
    function pesel($pesel, &$birth)
    {
        static $weights_pesel = array(1,3,7,9,1,3,7,9,1,3);

        $birth = array(false,false);

        // remove any dashes, spaces, returns, tabs or slashes
        $pesel = str_replace(array('-','/',' ',"\t","\n"), '', $pesel);

        // check if this is a 11-digit number
        if (!is_numeric($pesel) || strlen($pesel) != 11) {
            return false;
        }

        if (Validate::_checkControlNumber($pesel,
                                          $weights_pesel,
                                          10,
                                          10) === false) {
            return false;
        }

        // now extract birth date from PESEL number
        $vy = substr($pesel, 0, 2);
        $vm = substr($pesel, 2, 2);
        $vd = substr($pesel, 4, 2);

        // decode century
        if ($vm < 20) {
            $vy += 1900;
        } elseif ($vm < 40) {
            $vy += 2000;
        } elseif ($vm < 60) {
            $vy += 2100;
        } elseif ($vm < 80) {
            $vy += 2200;
        } else {
            $vy += 1800;
        }
        $vm      %= 20;
        $birth[0] = "$vy-$vm-$vd";

        // decode gender
        $gender   = substr($pesel, 9, 1) % 2;
        $birth[1] = ($gender % 2 == 0) ? 'female' : 'male';

        return true;
    }

    /**
     * Validates province code
     *
     * @param string $region 2 digit string
     *
     * @return bool
     * @see http://pl.wikipedia.org/wiki/Wojew%C3%B3dztwo
     */
    function region($region)
    {
        $regions = array('02','04','06','08','10','12','14','16','18',
                         '20','22','24','26','28','30','32' );
        return in_array($region, $regions);
    }

    /**
     * Validates full province name
     *
     * @param string $region full name
     *
     * @return bool
     */
    function regionFull($region)
    {
        $regions = array('dolnośląskie','kujawsko-pomorskie','lubelskie',
                         'lubuskie','mazowieckie','małopolskie','opolskie',
                         'podkarpackie','podlaskie','pomorskie',
                         'warmińsko-mazurskie','wielkopolskie','zachodniopomorskie',
                         'łódzkie','śląskie','świętokrzyskie' );
        return in_array($region, $regions);
    }

    /**
     * Validates REGON (Polish statistical national economy register)
     *
     * Sprawdza REGON (Rejestr Gospodarki Narodowej)
     * http://chemeng.p.lodz.pl/zylla/ut/nip-rego.html
     *
     * @param string $regon 9- or 14- digit number to validate
     *
     * @return bool
     */
    function regon($regon)
    {
        static $weights_regon       = array(8,9,2,3,4,5,6,7);
        static $weights_regon_local = array(2,4,8,5,0,9,7,3,6,1,2,4,8);

        // remove any dashes, spaces, returns, tabs or slashes
        $regon = str_replace(array('-','/',' ',"\t","\n"), '', $regon);

        // check if this is a 9- or 14-digit number
        if (!is_numeric($regon) || (strlen($regon) != 9 && strlen($regon) != 14)) {
            return false;
        }

        // first check first 9 digits
        if (Validate::_checkControlNumber($regon, $weights_regon, 11) === false) {
            return false;
        }

        // check wide number if there are 14 digits
        if (strlen($regon) == 14) {
            // check 14 digits
            return Validate::_checkControlNumber($regon, $weights_regon_local, 11);
        }

        return true;
    }

    /**
     * Validates a PL Postal Code format (ZIP code)
     *
     * @param string $postalCode the ZIP code to validate
     * @param bool   $strong     optional; strong checks (e.g. against a list 
     *                           of postcodes) (not implanted)
     *
     * @return boolean TRUE if code is valid, FALSE otherwise
     * @see http://pl.wikipedia.org/wiki/Kod_pocztowy#Kody_pocztowe_w_Polsce
     */
    function postalCode($postalCode, $strong = false)
    {
        return (bool)preg_match('/^\d{2}-\d{3}$/', $postalCode);
    }

    /**
     * Validates a car registration number
     *
     * @param string $reg the registration number
     *
     * @return bool
     * @see http://pl.wikipedia.org/wiki/Polskie_tablice_rejestracyjne
     */
    function carReg($reg)
    {
        $pregs = array(
            // 2 letter district
            "[a-z]{2}\d{5}",
            "[a-z]{2}\d{4}[a-z]{1}",
            "[a-z]{2}\d{3}[a-z]{2}",
            "[a-z]{2}\d{1}[a-z]{1}\d{3}",
            "[a-z]{2}\d{1}[a-z]{2}\d{2}",

            // 3 letter district
            "[a-z]{3}[a-z]{1}\d{3}",
            "[a-z]{3}\d{2}[a-z]{2}",
            "[a-z]{3}\d{1}[a-z]{1}\d{2}",
            "[a-z]{3}\d{2}[a-z]{1}\d{1}",
            "[a-z]{3}\d{1}[a-z]{2}\d{1}",
            "[a-z]{3}[a-z]{2}\d{2}",
            "[a-z]{3}\d{5}",
            "[a-z]{3}\d{4}[a-z]{1}",
            "[a-z]{3}\d{3}[a-z]{2}",
            "[a-z]{3}[a-z]{1}\d{2}[a-z]{1}",
            "[a-z]{3}[a-z]{1}\d{1}[a-z]{2}",

            // bikes
            "[a-z]{2}\d{4}",
            "[a-z]{2}\d{3}[a-z]{1}",
            "[a-z]{3}[a-z]{1}\d{3}", // deprecated

            // temporaty
            "[a-z]{1}\d{1}\d{4}",
            "[a-z]{1}\d{1}\d{3}B",

            // individual
            "[a-z]{1}\d{1}[a-z]{3}[a-z0-9]{0,2}",

            // classic
            "[a-z]{2}\d{2}[a-z]{1}",
            "[a-z]{2}\d{3}",
            "[a-z]{3}\d{1}[a-z]{1}",
            "[a-z]{3}\d{2}",
            "[a-z]{3}[a-z]{1}\d{1}",

            // diplomatic
            "W\d{6}",

            // military
            "U[abcdegijk]\d{4,5}T?",

            // special services
            "H[apmwkbcsn][a-z][a-z]{1}\d{3}",
            "H[apmwkbcsn][a-z]\d{2}[a-z]{2}");
        foreach ($pregs as $preg) {
            if (preg_match('/^'.$preg.'$/i', $reg)) {
                return true;
            }
        }
        return false;
    }
}
?>
