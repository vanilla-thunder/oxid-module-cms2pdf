<?php
/**
 * [___VENDOR___] ___NAME___
 * Copyright (C) ___YEAR___  ___COMPANY___
 * info:  ___EMAIL___
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 **/

require_once(dirname(__FILE__) . "/tcpdf/config/tcpdf_config.php");
require_once(dirname(__FILE__) . "/tcpdf/tcpdf.php");

class cms2pdf extends oxSuperCfg
{

	// module activation event
	static function install ()
	{
		/** @var oxDbMetaDataHandler $oMetaData */
		$oMetaData = oxNew('oxDbMetaDataHandler');
		oxRegistry::get("oxUtils")->writeToLog("\nbla cms2pdf installation - event","custom.log");
		if (!$oMetaData->fieldExists("BLAPDF", "oxcontents"))
		{
			oxRegistry::get("oxUtils")->writeToLog("\nbla cms2pdf installation - adding fields","custom.log");

			$q = "ALTER TABLE `oxcontents` ADD `BLAPDF` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'bla cms2pdf - pdf file name', ADD `BLAPDF_1` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'bla cms2pdf lang #1 - pdf file name', ADD `BLAPDF_2` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'bla cms2pdf lang #2 - pdf file name'";
			oxRegistry::get("oxUtils")->writeToLog("\n".$q,"custom.log");
			$oDb = oxDb::getDb();
			$oDb->execute($q);

			$oMetaData->updateViews();

			//clear tmp
			$dir = oxRegistry::getConfig()->getConfigParam("sCompileDir") . "smarty/*";
			foreach (glob($dir) as $item) if (!is_dir($item)) unlink($item);
		}
	}

	public function generate ()
	{
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}

	/**
	 * generate pdf file from cms page
	 *
	 * @param oxcontent_cms2pdf $cms
	 *
	 * @return bool
	 * @throws oxSystemComponentException
	 */
	public function saveAsPDF (oxContent $cms)
	{
		$cfg = oxRegistry::getConfig();
		$shop = $cfg->getActiveShop();

		$log = $cfg->getConfigParam("bla_cms2pdf_blLog");
		// if ($log) oxRegistry::getUtils()->writeToLog("","cms2pdf.log");

		$this->setAdminMode(false);

		// preparing file path anad name

		$sDir = $cfg->getConfigParam("sShopDir") . $cfg->getConfigParam("bla_cms2pdf_sPdfDir");
		if (!is_dir($sDir) && !mkdir($sDir, 0777))
		{
			oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException("can not create $sDir directory, please check permissions or create it manually"));
			return;
		}

		$sLang = oxRegistry::getLang()->getLanguageAbbr($cms->getLanguage());
		if (!is_dir($sDir.DIRECTORY_SEPARATOR.$sLang) && !mkdir($sDir.DIRECTORY_SEPARATOR.$sLang, 0777))
		{
			oxRegistry::get("oxUtilsView")->addErrorToDisplay(new oxException("can not create {$sDir}/{$sLang} directory, please check permissions or create it manually"));
			return;
		}

		$sFileName = $cms->generatePdfname();
		$sFile = $sDir.$sLang.DIRECTORY_SEPARATOR.$sFileName;

		if ($log) oxRegistry::getUtils()->writeToLog("generating new PDF file from ".$cms->oxcontents__oxloadid->value." {$sLang}\n", "cms2pdf.log");
		if ($log) oxRegistry::getUtils()->writeToLog("combining file name and path from:\n\tpdf path {$sDir} \n\tlang abbr: {$sLang} \n\tcms title: ".$cms->getTitle()." \n\tfile name: {$sFileName}\n\tresult: {$sFile}\n","cms2pdf.log");

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($shop->oxshops__oxcompany->value);
		$pdf->SetTitle($shop->oxshops__oxname->value);
		$pdf->SetSubject($cms->getTitle());

		if ($log) oxRegistry::getUtils()->writeToLog("PDF metadata:\n\tcreator: ".PDF_CREATOR."\n\tauthor: ".$shop->oxshops__oxcompany->value."\n","cms2pdf.log");

		// disable dedicated header
		$pdf->setHeaderData();
		$pdf->setPrintHeader(false);


		// set margins
		$pdf->SetMargins(20, 10, 15);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(10);

		// set auto page breaks
		$pdf->SetAutoPageBreak(true, 10);

		$pdf->AddPage();

		//header auf der ersten Seite
		$link = (strpos($cms->getLink(), '?')) ? substr($cms->getLink(), 0, strpos($cms->getLink(), '?')) : $cms->getLink();
		$aHeaderVars = [
			[
				'$shopname',
				'$shoplogo',
				'$shopurl',
				'$cmsname',
				'$cmsurl'
			],
			[
				$shop->oxshops__oxname->value,
				$cfg->getImageUrl(false, null, null, 'logo.png'),
				//'<img src="'.$cfg->getImageUrl(false, null, null, 'logo.png').'"/>',
				$cfg->getShopUrl(null, false),
				$cms->getTitle(),
				$link
			]
		];
		$header = str_replace($aHeaderVars[0], $aHeaderVars[1], implode("", $cfg->getConfigParam("bla_cms2pdf_aPdfHeader")));

		// cms content
		//$oUtilsView = oxRegistry::get("oxUtilsView");
		//$content = $oUtilsView->parseThroughSmarty($cms->oxcontents__oxcontent->value, $cms->getId(), null, true);

		// fetch
		$content = file_get_contents($link . "?plain=1");
		if ($log) oxRegistry::getUtils()->writeToLog("fetching content from {$link}?plain=1\n\t".$cms->oxcontents__oxloadid->value."_".$sLang."_original.html\n","cms2pdf.log");
		if ($log) oxRegistry::getUtils()->writeToLog($content,"cms2pdf/".$cms->oxcontents__oxloadid->value."_".$sLang."_original.html");

		// clean up
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
		$content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $content);
		$content = preg_replace('/<link\b[^>]*>(.*?)(<\/link>)?/is', "", $content);
		if ($log) oxRegistry::getUtils()->writeToLog("removing script and style tags from fetched content\n\t".$cms->oxcontents__oxloadid->value."_".$sLang."_clean.html\n","cms2pdf.log");
		if ($log) oxRegistry::getUtils()->writeToLog($content,"cms2pdf/".$cms->oxcontents__oxloadid->value."_".$sLang."_clean.html");

		$html = $header.$content;

		// tidy html code
		if(function_exists("tidy_parse_string"))
		{
			/** @var tidy $html */
			$html = tidy_parse_string($header . $content, [
				'show-body-only' => true,
				'wrap'           => 0
			], 'UTF8');
			$html->cleanRepair();
			if ($log) oxRegistry::getUtils()->writeToLog("cleaning up HTML with tidy\n\t".$cms->oxcontents__oxloadid->value."_".$sLang."_tidy.html\n","cms2pdf.log");
			if ($log) oxRegistry::getUtils()->writeToLog($html,"cms2pdf/".$cms->oxcontents__oxloadid->value."_".$sLang."_tidy.html");
		}


		$pdf->writeHTML($html, true, false, true, false, '');

		// QRCODE
		if ($cfg->getConfigParam('bla_cms2pdf_blPrintQR'))
		{
			$pdf->setPage(1);
			$qrStyle = [
				'border'        => 0,
				'vpadding'      => 1,
				'hpadding'      => 1,
				'fgcolor'       => [
					0,
					0,
					0
				],
				'position'      => 'R',
				'module_width'  => 1,
				// width of a single module in points
				'module_height' => 1
				// height of a single module in points
			];
			$pdf->write2DBarcode($link, 'QRCODE,L', $pdf->getPageDimensions(1)['wk'] - 50, 5, 50, 50, $qrStyle, 'T');
		}

		if ($log) oxRegistry::getUtils()->writeToLog("saving PDF file to {$sFile}\n","cms2pdf.log");
		$pdf->output($sFile, 'F');

		$this->setAdminMode(true);

		return $sLang.DIRECTORY_SEPARATOR.$sFileName;
	}
}

