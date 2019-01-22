<?php
/**
 * [bla] cms2pdf
 * Copyright (C) 2019  bestlife AG
 * info:  oxid@bestlife.ag
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 **/

$style = '<style type="text/css">
.groupExp a.rc b {font-size:medium;color:#ff3600;}
.groupExp dt input.txt {width:430px !important}
.groupExp dt .select {width:437px !important;}
.groupExp dt textarea.txtfield {width:430px height: 150px;}
.groupExp dl { display:block !important;}
input.confinput {position:fixed;top:20px;right:70px;background:#008B2D;padding:10px 25px;color:white;border:1px solid black;cursor:pointer;font-size:125%;}
input.confinput:hover {outline:3px solid #ff3600;}
</style>';
$sLangName = 'Deutsch';
$aLang = [
	'charset'                            	=> 'UTF-8',
	'SHOP_MODULE_GROUP_bla_cms2pdf_Main' 	=> 	$style . 'Einstellungen',
	'SHOP_MODULE_bla_cms2pdf_sPdfDir'       => 'Verzeichnis für PDF Dateien <em>(muss unterhalb des Shop Hauptordners sein)</em>',
	'SHOP_MODULE_bla_cms2pdf_aPdfHeader'    => 'Header für PDF Dateien <em>(kann HTML und Smarty enthalten)</em><br/><u><strong>erlaubte Variablen/Objekte:</strong></u><ul><li><strong>$shopname</strong> = Name des Shops</li><li><strong>$shopurl</strong> = Haupt-URL des Shops</li><li><strong>$shoplogo</strong> = Shop Logo url (logo.png)</li><li><strong>$cmsname</strong> = Titel der CMS Seite</li><li><strong>$cmsurl</strong> = URL der CMS Seite</li></ul>',
	'SHOP_MODULE_bla_cms2pdf_sPdfReaderUrl' => 'Link zum PDF Reader',
	'SHOP_MODULE_bla_cms2pdf_blPrintQR'     => 'Link zu der CMS Seite als QR Code rechts oben abbilden?',
	'SHOP_MODULE_bla_cms2pdf_blLog'			=> 'Logs antivieren: allgemeine Daten, wie Dateinamen und Pfade werden in log/cms2pdf.log geschrieben, sofern php tidy extension aktiv ist, werden unter log/ auch die "vorher und nachher" Versionen als html Dateien gespeichert.'
];