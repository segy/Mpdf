# Mpdf component for generating PDF files from HTML

mPDF is a great class that can create PDF files from HTML. For more information see mPDF homepage: http://www.mpdf1.com/mpdf/index.php

I wrote this component to easily use mPDF with cake views. You just need to initialize Mpdf component, set desired layout (view) and instead of standard output the PDF file will be generated.

This plugin is for CakePHP 2.x

## Short example in controller

Basic example:

    public $components = array('Mpdf.Mpdf');

    public function testpdf() {
        // initializing mPDF
        $this->Mpdf->init();

        // setting filename of output pdf file
        $this->Mpdf->setFilename('file.pdf');

        // setting output to I, D, F, S
        $this->Mpdf->setOutput('D');

        // you can call any mPDF method via component, for example:
        $this->Mpdf->SetWatermarkText("Draft");
    }

## Example for calling from another method

When called from another method (i.e. sending email with attachment), you have to call it via requestAction with:

    /**
     * Method generating PDF and saving it as file
     */
    public function generate_pdf() {
        $this->Mpdf->init();

        // ... set params
        $this->Mpdf->setFilename('PATH.pdf');
        $this->Mpdf->setOutput('F');
    }

    /**
     * Method calling generate_pdf()
     */
    public function send_email() {
        $this->requestAction(array('action' => 'generate_pdf'), array('return', 'bare' => false))
        // ... use generated file
    }