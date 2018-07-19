<?php

class api_error implements ArrayAccess, JsonSerializable {
	private $_code;
	private $_message;
	private $_data;

	public function __construct($code, $message, $data = []) {
		$this->_code = $code;
		$this->_message = $message;
		$this->_data = $data ?: [];
	}

	public function __get($key) {
		switch ($key) {
			case 'code':
			case 'message':
				return $this->{"_$key"};
		}
	}

	public function offsetExists($key) {
		return array_key_exists($key, $this->_data);
	}

	public function offsetGet($key) {
		return @$this->_data[$key];
	}

	public function offsetSet($key, $value) {
		$this->_data[$key] = $value;
	}

	public function offsetUnset($key) {
		unset($this->_data[$key]);
	}

	public function jsonSerialize() {
		return [
			'code'    => $this->_code,
			'message' => $this->_message,
			'data'    => $this->_data
		];
	}
}
