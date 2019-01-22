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

$sMetadataVersion = '1.1';
$aModule = [
	'id'          => 'cms2pdf',
	'title'       => '<strong style="color:#95b900;font-size:125%;">best</strong><strong style="color:#c4ca77;font-size:125%;">life</strong> <strong>CMS to PDF</strong>',
	'description' => 'Dieses Modul ermöglicht die Bereitstellung von CMS Seiten wie AGB oder Widerrufsrecht als PDF Dateien.',
	'thumbnail'   => 'tinymce.png',
	'version'     => '1.3.0 ( 2019-01-21 )',
	'author'      => 'Marat Bedoev, bestlife AG',
	'email'       => 'oxid@bestlife.ag',
	'url'         => 'https://github.com/vanilla-thunder/oxid-module-cms2pdf',
	'extend'      => ['oxcontent' => 'bla/cms2pdf/application/extend/oxcontent_cms2pdf'],
	'files'       => [ 'cms2pdf' => 'bla/cms2pdf/application/files/cms2pdf.php'],
	'events'      => ['onActivate' => 'cms2pdf::install',],
	'blocks'      => [
	   ['template' => 'content_main.tpl', 'block' => 'admin_content_main_form', 'file' => '/application/views/blocks/admin_content_main_form.tpl'],
	   ['template' => 'layout/page.tpl',  'block' => 'content_main',            'file' => '/application/views/blocks/content_main.tpl']
   ],
	'settings'    => [
	   ['group' => 'bla_cms2pdf_Main', 'name' => 'bla_cms2pdf_sPdfDir', 'type' => 'str', 'position' => 1, 'value' => "pdf/"],
	   ['group' => 'bla_cms2pdf_Main', 'name' => 'bla_cms2pdf_aPdfHeader', 'type' => 'arr', 'position' => 2, 'value' => ['<a href="$shopurl"><img src="$shoplogo" height="65"/></a><br/>', '<a href="$cmsurl">$cmsurl</a><br/>','<hr/>']],
	   ['group' => 'bla_cms2pdf_Main', 'name' => 'bla_cms2pdf_sPdfReaderUrl', 'type' => 'str', 'position' => 3, 'value' => "http://get.adobe.com/reader/"],
	   ['group' => 'bla_cms2pdf_Main', 'name' => 'bla_cms2pdf_blPrintQR', 'type' => 'bool', 'position' => 4, 'value' => true],
	   ['group' => 'bla_cms2pdf_Main', 'name' => 'bla_cms2pdf_blLog', 'type' => 'bool', 'position' => 5, 'value' => false],
   ]
];
