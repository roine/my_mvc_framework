<?php

class Config{


	public static load($params = ''){
		if(empty($params)){
			throw new Exception('No params have been set');
		}
		print_r(explode('.', $params);
	}
}