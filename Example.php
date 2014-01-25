<?php
require('Dictionary.php');
$arrDictionary = array();
$arrDictionary['id']['type'] = Dictionary::UINT;
$arrDictionary['id']['optional'] = false;
$arrDictionary['content']['type'] = Dictionary::STRING;
$arrDictionary['content']['optional'] = false;
$arrDictionary['time']['type'] = Dictionary::DATE;
$arrDictionary['time']['optional'] = true;
$arrDictionary['time']['default'] = 'now';
$objDictionary = new Dictionary($arrDictionary);
$arrParams['id'] = '123';
$arrParams['content'] = 'abc';
$arrParams = $objDictionary->checkParams($arrParams);
var_dump($arrParams);
