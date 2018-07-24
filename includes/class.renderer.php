<?php

class renderer extends renderer_base {
	private static $_default_renderer = false;
	private static $_renderers = [];

	public static function load($resource = false) {
		if ($resource) {
			if (!isset(self::$_renderers[$resource])) {
				$class = "{$resource}_renderer";

				if (class_exists($class))
					self::$_renderers[$resource] = new $class();
				else
					self::$_renderers[$resource] = new self($resource);
			}

			return self::$_renderers[$resource];
		} else {
			if (!self::$_default_renderer)
				self::$_default_renderer = new self(false);

			return self::$_default_renderer;
		}
	}

	public function render($view, $controller = false) {
		$controller = controller::load($this->resource);
		$controller->pre_render($view, $result);

		$this->render_result($result);
	}

	// TODO backport to PHPunk
	protected function render_result($result) {
		if ($result instanceof database_record) {
			$response = $this->create_response($result);
		} elseif ($result instanceof database_result) {
			$response = [];
			foreach ($result as $record)
				$response[] = $this->create_response($record);
		} elseif ($result instanceof api_error) {
			if (isset($result['status'])) {
				http_response_code($result['status']);
				unset($result['status']);
			}

			$response = $result;
		} else {
			$response = new api_error('api_invalid_response',
				'The response was invalid.');

			http_response_code(500);
		}

		header('Content-type: text/json');
		echo json_encode($response);
	}
}
