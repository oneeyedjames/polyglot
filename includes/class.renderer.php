<?php

use PHPunk\Component\renderer as renderer_base;

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

	public function render($view) {
		$controller = controller::load($this->resource);
		$controller->pre_render($view, $result);

		$this->render_result($result);
	}
}
