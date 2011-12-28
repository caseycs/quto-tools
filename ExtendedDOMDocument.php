<?php
namespace Tools;

class ExtendedDOMDocument extends \DOMDocument
{
	/**
	 * Создает элемент с CDATA
	 * 
	 * @param string $elementName
	 * @param mixed $data
	 * @return \DOMElement
	 */
	public function createElementWithCDATA($elementName, $data)
	{
		$element = $this->createElement($elementName);
		$elementCDATA = $this->createCDATASection($data);
		$element->appendChild($elementCDATA);
	
		return $element;
	}
	
	/**
	 * Создает элемент с текстом
	 * 
	 * @param string $elementName
	 * @param string $text
	 * @return \DOMElement
	 */
	public function createElementWithText($elementName, $text)
	{
		$element = $this->createElement($elementName);
		$elementText = $this->createTextNode($text);
		$element->appendChild($elementText);
	
		return $element;
	}
	
	/**
	 * Отличается ли файл от существующего XML
	 * 
	 * @param string $fileName
	 * @return boolean
	 */
	public function isDifferentFrom($fileName)
	{
		// Если файла нет, то в любом случае текущий XML новее
		if (!file_exists($fileName)) {
			return TRUE;
		}
		
		return (crc32($this->saveXML()) != crc32(file_get_contents($fileName)));
	}
}
