<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Coedition\EDI\ASCX12;

// Originally the Perl version used these HEX values, but that wasn't working very well in PHP
//$ascx12 = new ASCX12('\x0A','\x7C');
$ascx12 = new ASCX12("\n","|",'\x1F',$only_catalog_type = false);
/* write to file */
//$ascx12->convertfile('example/997-sample.edi', 'output.xml',$pretty_print = false);

/* write to stdout */
//$data = $ascx12->convertdata(file_get_contents('997-sample2.edi'), $pretty_print = true);
//$data = $ascx12->convertdata(file_get_contents('846-sample2.edi'), $pretty_print = true);
//$data = $ascx12->convertdata(file_get_contents('856-sample3.edi'), $pretty_print = true);
//$data = $ascx12->convertdata(file_get_contents('850_20180403113400.txt'), $pretty_print = true);
$data = $ascx12->convertdata(file_get_contents('856-nydj2.edi'), $pretty_print = true, 856);
echo $data;
echo "finished";

