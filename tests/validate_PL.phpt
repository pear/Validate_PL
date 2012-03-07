--TEST--
validate_PL.phpt: Unit tests for Validation of Polish data.
--FILE--
<?php
$noYes = array('KO', 'OK');
$symbol = array('!X!', ' V ');

include (dirname(__FILE__).'/validate_functions.inc');

if (is_file(dirname(__FILE__) . '/../Validate/PL.php')) {
    require_once dirname(__FILE__) . '/../Validate/PL.php';
} else {
    require 'Validate/PL.php';
}

$validate = new Validate_PL;
echo "Test Validate_PL\n";
echo "****************\n";

$nips = array("7680002466","3385035171",);
$regons = array("590096454", "590096455","002188077");
$pesels = array("40121008916","60052219867","33090901995","49110603787",
                "14665303253","42176454020","30261330924","18659635322");
$banks = array("94332544", "94332557", "94332560", "94332573", "94332586",
               "94332537", "94332538", "94332539", "94332540", "94332541");
$postalCodes = array("00-000", "99-999");
$carRegs = array("XY12345","XY1234A","XY123AC","XY1A234",
				 "XY1AC23","XYZA123","XYZ12AC","XYZ1A23",
				 "XYZ12A3","XYZ1AC2","XYZAC12","XYZ12345",
				 "XYZ1234A","XYZ123AC","XYZA12C","XYZA1CE",
				 "XY1234","XY123A","X12345","X1234B","X1ABCDE",
				 "X1ABC23","XY12A","XY123","XYZ1A","XYZ12",
				 "XYZA1","W123456","UA12345","UA1234","UA12345T",
				 "HPPE057");
$regionCodes = array("00","02");
$regionNames = array("pomorskie","jakiestam");

echo "\nTest NIP\n";
foreach($nips as $nip) {
    printf("%s: %s\n", $nip, $noYes[$validate->nip($nip)]);
}
echo "\nTest REGON\n";
foreach($regons as $regon) {
    printf("%s: %s\n", $regon, $noYes[$validate->regon($regon)]);
}
echo "\nTest PESEL\n";
foreach($pesels as $pesel) {
    printf("%s: %s\n", $pesel, $noYes[$validate->pesel($pesel,$dn)]);
}
echo "\nTest Bank Branch\n";
foreach($banks as $bank) {
    printf("%s: %s\n", $bank, $noYes[$validate->bankBranch($bank,$dn)]);
}
echo "\nTest Postal Code\n";
foreach($postalCodes as $postalCode) {
    printf("%s: %s\n", $postalCode, $noYes[$validate->postalCode($postalCode)]);
}
echo "\nTest Car Registration Number\n";
foreach($carRegs as $carReg) {
    printf("%s: %s\n", $carReg, $noYes[$validate->carReg($carReg)]);
}
echo "\nTest Region Codes\n";
foreach($regionCodes as $code) {
    printf("%s: %s\n", $code, $noYes[$validate->region($code)]);
}
echo "\nTest Region Names\n";
foreach($regionNames as $name) {
    printf("%s: %s\n", $name, $noYes[$validate->regionFull($name)]);
}
exit(0);
?>

--EXPECT--
Test Validate_PL
****************

Test NIP
7680002466: OK
3385035171: KO

Test REGON
590096454: OK
590096455: KO
002188077: OK

Test PESEL
40121008916: OK
60052219867: OK
33090901995: OK
49110603787: OK
14665303253: OK
42176454020: KO
30261330924: KO
18659635322: KO

Test Bank Branch
94332544: OK
94332557: OK
94332560: OK
94332573: OK
94332586: OK
94332537: KO
94332538: KO
94332539: KO
94332540: KO
94332541: KO

Test Postal Code
00-000: OK
99-999: OK

Test Car Registration Number
XY12345: OK
XY1234A: OK
XY123AC: OK
XY1A234: OK
XY1AC23: OK
XYZA123: OK
XYZ12AC: OK
XYZ1A23: OK
XYZ12A3: OK
XYZ1AC2: OK
XYZAC12: OK
XYZ12345: OK
XYZ1234A: OK
XYZ123AC: OK
XYZA12C: OK
XYZA1CE: OK
XY1234: OK
XY123A: OK
X12345: OK
X1234B: OK
X1ABCDE: OK
X1ABC23: OK
XY12A: OK
XY123: OK
XYZ1A: OK
XYZ12: OK
XYZA1: OK
W123456: OK
UA12345: OK
UA1234: OK
UA12345T: OK
HPPE057: OK

Test Region Codes
00: KO
02: OK

Test Region Names
pomorskie: OK
jakiestam: KO