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

class oxcontent_cms2pdf extends oxcontent_cms2pdf_parent {

	public function getPdfLink() 
	{
		if(!$this->oxcontents__blapdf->value) return false;

		return str_replace(
			oxRegistry::getConfig()->getConfigParam("sShopDir"),
			oxRegistry::getConfig()->getShopMainUrl(),
			$this->getPdfPath()
		);

		return oxRegistry::getConfig()->getShopUrl().oxRegistry::getConfig()->getConfigParam("bla_cms2pdf_sPdfDir").$this->oxcontents__blapdf->value;
		//var_dump(oxRegistry::getConfig()->getShopUrl());
		//var_dump(oxRegistry::getConfig()->getConfigParam("bla_cms2pdf_sPdfDir"));
		//var_dump($this->oxcontents__blapdf->value);
		//return preg_replace('#/+#','/',);
	}
	public function getPdfPath() 
	{
		if(!$this->oxcontents__blapdf->value ) return false;
		return oxRegistry::getConfig()->getConfigParam("sShopDir").oxRegistry::getConfig()->getConfigParam("bla_cms2pdf_sPdfDir").$this->oxcontents__blapdf->value;
	}

	public function getPdfReaderLink() {
		return oxRegistry::getConfig()->getConfigParam("bla_cms2pdf_sPdfReaderUrl");
	}
	
	public function generatePdfname()
	{
		$encoder = oxRegistry::get("oxSeoEncoder");
		$sFile = $this->oxcontents__oxtitle->value;
		$sFile = $encoder->encodeString($sFile, true, 0);
		$sFile = preg_replace("/[^A-Za-z0-9" . preg_quote('-', '/') . " \t\/]+/", '', $sFile);
		$sFile = preg_replace("/[^A-Za-z0-9" . preg_quote('-', '/') . "\/]+/", '_', $sFile);
		$sFile = $sFile . '.pdf'; //  .pdf anhängen
		return $sFile;
	}
	
    public function saveAsPDF($generate)
    {
		$log = oxRegistry::getConfig()->getConfigParam("bla_cms2pdf_blLog");
		if ($log) oxRegistry::get("oxUtils")->writeToLog("\n-------------------------------------  ".date("Y-m-d H:i:s")."\n\n","cms2pdf.log");
		if ($log) oxRegistry::get("oxUtils")->writeToLog("saving cms page ".$this->oxcontents__oxloadid->value." as pdf\n","cms2pdf.log");

		$act = ( $this->oxcontents__oxactive->value == 1 ) ? true : false;
		$pdf = ( $this->oxcontents__blapdf->value ) ? true : false;

        $save = false;

        // alte pdf Datei löschen
        if($pdf)
        {
        	$file = $this->getPdfPath();
        	if (is_file($file))
        	{
				if ($log) oxRegistry::get("oxUtils")->writeToLog("remove ".$file."\n","cms2pdf.log");
				unlink($file);
        	}
           	$this->oxcontents__blapdf->value = '';
            $save = true;
        }

        if($act && $generate) // cms active and we want pdf version
        {
            // generate pdf
            //oxRegistry::get("oxUtils")->writeToLog("generating new pdf file!\n","custom.log");
            if($filename = oxRegistry::get("cms2pdf")->saveAsPDF($this))
            {
            	// alles okay
            	$this->oxcontents__blapdf->value = $filename;
                $save = true;
				if ($log) oxRegistry::getUtils()->writeToLog("done!\n","cms2pdf.log");
            }
            else
			{
            	// fehler
            	oxRegistry::get('oxUtilsView')->addErrorToDisplay("could not generate pdf file!\n");
				if ($log) oxRegistry::getUtils()->writeToLog("ERROR!\n","cms2pdf.log");
            }
        }

        if($save) parent::save();
    }

    public function save()
    {
        $ret = parent::save();
        if($ret) $this->saveAsPDF(oxRegistry::getConfig()->getRequestParameter("generatepdf",true));
        return $ret;
    }
}
