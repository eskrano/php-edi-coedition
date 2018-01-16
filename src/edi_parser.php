<?php
include_once('ASCX12.php');
include_once('Catalogs/Catalog832.php');
include_once('Catalogs/Catalog846.php');
include_once('Catalogs/Catalog850.php');
include_once('Catalogs/Catalog855.php');
include_once('Catalogs/Catalog856.php');
include_once('Catalogs/Catalog997.php');
include_once('Catalogs/Catalogs.php');
include_once('Catalogs/Segments.php');

//$ascx12 = new \Coedition\EDI\ASCX12('\x0A','\x7C');
$ascx12 = new \Coedition\EDI\ASCX12("\n","|");
/* write to file */
$ascx12->convertfile('Catalogs/997-sample.edi', 'output.xml',$pretty_print = false);

/* write to stdout */
//$data = $ascx12->convertdata(file_get_contents('Catalogs/997-sample.edi'), $pretty_print = false);
//echo $data;
echo "finished";

