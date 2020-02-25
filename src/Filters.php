<?php

namespace Breadhead\Paddle;

/**
 * Paddle.com API input filters
 * All methods validates, filters and returns provided values
 * @author Paddle.com
 */
class Filters {

	public static function filter_product_id($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_300, 300);
		} else {
			return $value;
		}
	}

	public static function filter_title($value) {
		if (!is_string($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_301, 301);
		} else {
			return $value;
		}
	}

	public static function filter_image_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_302, 302);
		} else {
			return $value;
		}
	}

	/** @deprecated use filterPrice */
	public static function filter_price($value) {
		return self::filterPrice($value);
	}

    public static function filterPrice($value) {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_303, 303);
        } elseif ($value < 0) {
            throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_304, 304);
        } else {
            return $value;
        }
    }

    public static function filterPrices(array $values) {
	    return array_filter(
            $values,
            function($val){
                $res = explode(':', $val);
                
                if (count($res) <> 2 || (!is_float($res[1]) && !is_numeric($res[1])) || $res[1] < 0) {
                    throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_139, 139);
                }

                return true;
            }
        );
    }

	public static function filter_return_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_305, 305);
		} else {
			return $value;
		}
	}

	public static function filter_discountable($value) {
		return ($value) ? 1 : 0;
	}

	public static function filter_locker_visible($value) {
		return ($value) ? 1 : 0;
	}

	public static function filter_quantity_variable($value) {
		return ($value) ? 1 : 0;
	}

	public static function filter_paypal_cancel_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_306, 306);
		} else {
			return $value;
		}
	}

	public static function filter_expires($value) {
		if ((!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_307, 307);
		} else if ($value < time()) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_308, 308);
		} else {
			return date('Y-m-d', $value);
		}
	}

	public static function filter_is_popup($value) {
		return ($value) ? 'true' : null;
	}

	public static function filter_parent_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_309, 309);
		} else {
			return $value;
		}
	}

	public static function filter_affiliates($value) {
		if (!is_array($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_310, 310);
		}

		$affiliates = array();

		foreach ($value as $key => $value) {
			// validate affiliates array structure
			if (empty($key) || empty($value)) {
				throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_311, 311);
			}

			$affiliates[] = $key . ':' . $value;
		}

		return $affiliates;
	}

	public static function filter_stylesheets($value) {
		if (!is_array($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_312, 312);
		}

		foreach ($value as $key => $value) {
			// validate stylesheets array structure
			if (empty($key) || empty($value)) {
				throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_313, 313);
			}
		}

		return $value;
	}

	public static function filter_webhook_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_315, 315);
		} else {
			return $value;
		}
	}

	public static function filter_limit($value) {
		if (!is_int($value) || $value < 1) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_319, 319);
		} else {
			return $value;
		}
	}

	public static function filter_offset($value) {
		if (!is_int($value) || $value < 0) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_320, 320);
		} else {
			return $value;
		}
	}

	public static function filter_start_timestamp($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_321, 321);
		} else {
			return date('Y-m-d H:i:s', $value);
		}
	}

	public static function filter_end_timestamp($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_322, 322);
		} else {
			return date('Y-m-d H:i:s', $value);
		}
	}

	public static function filter_email($value) {
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_323, 323);
		} else {
			return $value;
		}
	}

	public static function filter_application_icon_url($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_324, 324);
		} else {
			return $value;
		}
	}

}
