<?php

namespace Breadhead\Paddle;

/**
 * Paddle.com API input filters
 * All methods validates, filters and returns provided values
 * @author Paddle.com
 */
class Filters {

    /**
     * @param int $value
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function filterProductId($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_300, 300);
		} else {

			return $value;
		}
	}

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterTitle($value) {
		if (!is_string($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_301, 301);
		} else {

			return $value;
		}
	}

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterImageUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_302, 302);
		} else {

			return $value;
		}
	}

    /**
     * @param int $value
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function filterPrice($value) {
		if (!is_numeric($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_303, 303);
		} elseif ($value < 0) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_304, 304);
		} else {

			return $value;
		}
	}

    /**
     * @param string $value - url
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterReturnUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_305, 305);
		} else {

			return $value;
		}
	}

    /**
     * @param $value
     * @return int
     */
    public static function filterDiscountable($value) {
		return ($value) ? 1 : 0;
	}

    /**
     * @param $value
     * @return int
     */
    public static function filterLockerVisible($value) {
		return ($value) ? 1 : 0;
	}

    /**
     * @param $value
     * @return int
     */
    public static function filterQuantityVariable($value) {
		return ($value) ? 1 : 0;
	}

    /**
     * @param $value
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function filterPaypalCancelUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_306, 306);
		} else {

			return $value;
		}
	}

    /**
     * @param $value
     * @return false|string - date
     * @throws \InvalidArgumentException
     */
    public static function filterExpires($value) {
		if ((!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_307, 307);
		} else if ($value < time()) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_308, 308);
		} else {

			return date('Y-m-d', $value);
		}
	}

    /**
     * @param $value
     * @return null|string
     */
    public static function filterIsPopup($value) {
		return ($value) ? 'true' : null;
	}

    /**
     * @param $value
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function filterParentUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_309, 309);
		} else {

			return $value;
		}
	}

    /**
     * @param array $value
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function filterAffiliates($value) {
		if (!is_array($value)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_310, 310);
		}
		$affiliates = array();
		foreach ($value as $key => $v) {
			// validate affiliates array structure
			if (empty($key) || empty($v)) {
				throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_311, 311);
			}

			$affiliates[] = $key . ':' . $v;
		}

		return $affiliates;
	}

    /**
     * @param array $value
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function filterStylesheets($value) {
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

    /**
     * @param string $value - url
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterWebhookUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_315, 315);
		} else {

			return $value;
		}
	}

    /**
     * @param int $value
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function filterLimit($value) {
		if (!is_int($value) || $value < 1) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_319, 319);
		} else {

			return $value;
		}
	}

    /**
     * @param int $value
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function filterOffset($value) {
		if (!is_int($value) || $value < 0) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_320, 320);
		} else {

			return $value;
		}
	}

    /**
     * @param int $value - timestamp
     * @return false|string - date
     * @throws \InvalidArgumentException
     */
    public static function filterStartTimestamp($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_321, 321);
		} else {

			return date('Y-m-d H:i:s', $value);
		}
	}

    /**
     * @param int $value - timestamp
     * @return false|string - date
     * @throws \InvalidArgumentException
     */
    public static function filterEndTimestamp($value) {
		if (
			(!filter_var($value, FILTER_VALIDATE_INT) || !is_numeric($value))
		) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_322, 322);
		} else {

			return date('Y-m-d H:i:s', $value);
		}
	}

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterEmail($value) {
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_323, 323);
		} else {

			return $value;
		}
	}

    /**
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function filterApplicationIconUrl($value) {
		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(\Breadhead\Paddle\Api::ERR_324, 324);
		} else {

			return $value;
		}
	}

}
