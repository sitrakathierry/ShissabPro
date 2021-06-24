<?php

namespace AppBundle\Service;
use Spipu\Html2Pdf\Html2Pdf;

class Html2PdfService
{
	private $path;

	public function __construct($public_directory)
	{
		$this->path = $public_directory;
	}
	
	private $pdf;

	public function create()
	{
		$this->pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(1, 5, 5, 5));
    	$this->pdf->pdf->SetDisplayMode('fullpage');
		$this->includeFonts();
	}

	public function generatePdf($template, $name)
	{
		$this->pdf->writeHTML($template);
		return $this->pdf->Output($name . '.pdf');
	}

	/**
	 * utiliser le convertisseur en ligne pour convertir ttf en font reconu par html2pdf
	 * https://www.xml-convert.com/en/convert-tff-font-to-afm-pfa-fpdf-tcpdf
	 */
	public function includeFonts()
	{
		$this->pdf->addFont('Antiquet','', $this->path . 'fonts/typewriter/antiquet.php');
	}
}