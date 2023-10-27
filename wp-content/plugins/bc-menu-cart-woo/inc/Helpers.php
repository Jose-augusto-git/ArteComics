<?php


namespace BinaryCarpenter\BC_MNC;


class Helpers {

	public static function get_menu_array() {
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$menu_list = array();

		foreach ( $menus as $menu ) {
			$menu_list[] = array(
				'slug' => $menu->slug,
				'name' => $menu->name);
		}

		if (!empty($menu_list))
			return $menu_list;

		return array();
	}

}