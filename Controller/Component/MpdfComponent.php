<?php
/**
 * Component for working with mPDF class.
 * mPDF has to be in the vendors directory.
 */

class MpdfComponent extends Component {
	/**
	 * Instance of mPDF class
	 * @var object
	 */
	protected $pdf;
	/**
	 * Default values for mPDF constructor
	 * @var array
	 */
	protected $_configuration = array(
		// mode: 'c' for core fonts only, 'utf8-s' for subset etc.
		'mode' => 'utf8-s',
		// page format: 'A0' - 'A10', if suffixed with '-L', force landscape
		'format' => 'A4',
		 // default font size in points (pt)
		'font_size' => 0,
		// default font
		'font' => NULL,
		// page margins in mm
		'margin_left' => 15,
		'margin_right' => 15,
		'margin_top' => 16,
		'margin_bottom' => 16,
		'margin_header' => 9,
		'margin_footer' => 9
	);
	/**
	 * Flag set to true if mPDF was initialized
	 * @var bool
	 */
	protected $_init = false;
	/**
	 * Name of the file on the output
	 * @var string
	 */
	protected $_filename = NULL;
	/**
	 * Destination - posible values are I, D, F, S
	 * @var string
	 */
	protected $_output = 'I';
	
	/**
	 * Initialize 
	 * Add vendor and define mPDF class.
	 */
	public function init($configuration = array()) {
		// mPDF class has many notices - suppress them
		error_reporting(0);
		// import mPDF
		App::import('Vendor', 'mpdf/mpdf');
		if (!class_exists('mPDF'))
			throw new CakeException('Vendor class mPDF not found!');
		// override default values
		$c = array();
		foreach ($this->_configuration as $key => $val)
			$c[$key] = array_key_exists($key, $configuration) ? $configuration[$key] : $val;
		// initialize
		$this->pdf = new mPDF($c['mode'], $c['format'], $c['font_size'], $c['font'], $c['margin_left'], $c['margin_right'], $c['margin_top'], $c['margin_bottom'], $c['margin_header'], $c['margin_footer']);
		$this->_init = true;
	}
	
	/**
	 * Set filename of the output file 
	 */
	public function setFilename($filename) {
		$this->_filename = (string)$filename;
	}
	
	/**
	 * Set destination of the output 
	 */
	public function setOutput($output) {
		if (in_array($output, array('I', 'D', 'F', 'S')))
			$this->_output = $output;
	}
	
	/**
	 * Shutdown of the component
	 * View is rendered but not yet sent to browser.
	 */
	public function shutdown($controller) {
		if ($this->_init) {
			$this->pdf->WriteHTML((string)$controller->response);
			$this->pdf->Output($this->_filename, $this->_output);
			exit;
		}
	}
	
	/**
	 * Passing mathod cals and variable setting to mPDF library.
	 */
	public function __set($name, $value) {
		$this->pdf->$name = $value;
	}

	public function __get($name) {
		return $this->pdf->$name;
	}

	public function __isset($name) {
		return isset($this->pdf->$name);
	}

	public function __unset($name) {
		unset($this->pdf->$name);
	}
	
	public function __call($name, $arguments) {
		call_user_func_array(array($this->pdf, $name), $arguments);
	}
}
