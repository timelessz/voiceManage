<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


/**
 * PHPExcel_Shared_Escher_DggContainer_BstoreContainer
 *
 * @category   PHPExcel
 * @package    PHPExcel_Writer_Excel5
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Writer_Excel5_Escher
{
	/**
	 * The object we are writing
	 */
	private $_object;

	/**
	 * The written binary data
	 */
	private $_data;

	/**
	 * Shape offsets. Positions in binary stream where a new shape record begins
	 *
	 * @var array
	 */
	private $_spOffsets;

	/**
	 * Shape types.
	 *
	 * @var array
	 */
	private $_spTypes;
	
	/**
	 * Constructor
	 *
	 * @param mixed
	 */
	public function __construct($object)
	{
		$this->_object = $object;
	}

	/**
	 * Process the object to be written
	 */
	public function close()
	{
		// initialize
		$this->_data = '';

		switch (get_class($this->_object)) {

		case 'PHPExcel_Shared_Escher':
			if ($dggContainer = $this->_object->getDggContainer()) {
				$writer = new PHPExcel_Writer_Excel5_Escher($dggContainer);
				$this->_data = $writer->close();
			} else if ($dgContainer = $this->_object->getDgContainer()) {
				$writer = new PHPExcel_Writer_Excel5_Escher($dgContainer);
				$this->_data = $writer->close();
				$this->_spOffsets = $writer->getSpOffsets();
				$this->_spTypes = $writer->getSpTypes();
			}
			break;

		case 'PHPExcel_Shared_Escher_DggContainer':
			// this is a container record

			// initialize
			$innerData = '';

			// write the dgg
			$recVer			= 0x0;
			$recInstance	= 0x0000;
			$recType		= 0xF006;

			$recVerInstance  = $recVer;
			$recVerInstance |= $recInstance << 4;

			// dgg data
			$dggData =
				pack('VVVV'
					, $this->_object->getSpIdMax() // maximum shape identifier increased by one
					, $this->_object->getCDgSaved() + 1 // number of file identifier clusters increased by one
					, $this->_object->getCSpSaved()
					, $this->_object->getCDgSaved() // count total number of drawings saved
				);

			// add file identifier clusters (one per drawing)
			$IDCLs = $this->_object->getIDCLs();

			foreach ($IDCLs as $dgId => $maxReducedSpId) {
				$dggData .= pack('VV', $dgId, $maxReducedSpId + 1);
			}

			$header = pack('vvV', $recVerInstance, $recType, strlen($dggData));
			$innerData .= $header . $dggData;

			// write the bstoreContainer
			if ($bstoreContainer = $this->_object->getBstoreContainer()) {
				$writer = new PHPExcel_Writer_Excel5_Escher($bstoreContainer);
				$innerData .= $writer->close();
			}

			// write the record
			$recVer			= 0xF;
			$recInstance	= 0x0000;
			$recType		= 0xF000;
			$length			= strlen($innerData);

			$recVerInstance  = $recVer;
			$recVerInstance |= $recInstance << 4;

			$header = pack('vvV', $recVerInstance, $recType, $length);

			$this->_data = $header . $innerData;
			break;

		case 'PHPExcel_Shared_Escher_DggContainer_BstoreContainer':
			// this is a container record

			// initialize
			$innerData = '';

			// treat the inner data
			if ($BSECollection = $this->_object->getBSECollection()) {
				foreach ($BSECollection as $BSE) {
					$writer = new PHPExcel_Writer_Excel5_Escher($BSE);
					$innerData .= $writer->close();
				}
			}

			// write the record
			$recVer			= 0xF;
			$recInstance	= count($this->_object->getBSECollection());
			$recType		= 0xF001;
			$length			= strlen($innerData);

			$recVerInstance  = $recVer;
			$recVerInstance |= $recInstance << 4;

			$header = pack('vvV', $recVerInstance, $recType, $length);

			$this->_data = $header . $innerData;
			break;

		case 'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE':
			// this is a semi-container record

			// initialize
			$innerData = '';

			// here we treat the inner data
			if ($blip = $this->_object->getBlip()) {
				$writer = new PHPExcel_Writer_Excel5_Escher($blip);
				$innerData .= $writer->close();
			}

			// initialize
			$data = '';

			$btWin32 = $this->_object->getBlipType();
			$btMacOS = $this->_object->getBlipType();
			$data .= pack('CC', $btWin32, $btMacOS);

			$rgbUid = pack('VVVV', 0,0,0,0); // todo
			$data .= $rgbUid;

			$tag = 0;
			$size = strlen($innerData);
			$cRef = 1;
			$foDelay = 0; //todo
			$unused1 = 0x0;
			$cbName = 0x0;
			$unused2 = 0x0;
			$unused3 = 0x0;
			$data .= pack('vVVVCCCC', $tag, $size, $cRef, $foDelay, $unused1, $cbName, $unused2, $unused3);

			$data .= $innerData;

			// write the record
			$recVer			= 0x2;
			$recInstance	= $this->_object->getBlipType();
			$recType		= 0xF007;
			$length			= strlen($data);

			$