<?php
/**
 * Created by PhpStorm.
 * User: MYN
 * Date: 5/1/2019
 * Time: 9:42 AM
 */

namespace BinaryCarpenter\BC_MNC;
class Core
{
    const MENU_SLUG = 'bccomvn_wp_plugins_myn_menu';
    public function admin_menu()
    {
        if ( empty ( $GLOBALS['admin_page_hooks']['bccomvn_wp_plugins_myn_menu'] ) )
        {
            add_menu_page(
                'Binary Carpenter',
                'Binary Carpenter',
                'manage_options',
                self::MENU_SLUG,
                array($this,'bcvn_myn_general_menu'),
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IS0tIFVwbG9hZGVkIHRvOiBTVkcgUmVwbywgd3d3LnN2Z3JlcG8uY29tLCBHZW5lcmF0b3I6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDMyIDMyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjx0aXRsZT5maWxlX3R5cGVfYmluYXJ5PC90aXRsZT48cGF0aCBkPSJNMjAuOTI5LDJINC41MjlWMzBoMjMuM1Y5Wm01LjExNCwyNi4zNUg2LjQwOVYzLjY1SDE5LjgxNWw2LjMzMyw2LjMzM1YyOC4zNVpNMTEuNDc3LDE1LjM5M2MxLjU4NCwwLDIuNTYzLTEuNDYzLDIuNTYzLTMuNjYzLDAtMi4xNDUtLjgtMy40NDMtMi40LTMuNDQzUzkuMDY4LDkuNzUsOS4wNjgsMTEuOTVDOS4wNjgsMTQuMSw5Ljg3MSwxNS4zOTMsMTEuNDc3LDE1LjM5M1pNMTAuMjM0LDExLjczYzAtMS41NjIuNDI5LTIuNDUzLDEuMzItMi40NTMuNjQ5LDAsMS4wNDUuNTUsMS4yMjEsMS40NzRsLTIuNTMsMS4zMDlBMy4yLDMuMiwwLDAsMSwxMC4yMzQsMTEuNzNaTTExLjU2NSwxNC40Yy0uNjM4LDAtMS4wNDUtLjUyOC0xLjIyMS0xLjQzbDIuNTMtMS4zMDl2LjI4NkMxMi44NzQsMTMuNTEyLDEyLjQ1NiwxNC40LDExLjU2NSwxNC40Wm0xMC4yNy44NDcuMS0xLjAyM2gtMS42NVY4LjIxbC0xLjE3Ny4xdi44bC0xLjY5NC4xNzYuMDIyLjg5MSwxLjY3Mi0uMDQ0djQuMDkySDE3LjI1OVYxNS4yNVptLTcuODUsOS41LjEtMS4wMjNoLTEuNjVWMTcuNzFsLTEuMTc3LjF2LjhsLTEuNjk0LjE3Ni4wMjIuODkxLDEuNjcyLS4wNDR2NC4wOTJIOS40MDlWMjQuNzVabTUuNDQyLjE0M2MxLjU4NCwwLDIuNTYzLTEuNDYzLDIuNTYzLTMuNjYzLDAtMi4xNDUtLjgtMy40NDMtMi40LTMuNDQzcy0yLjU3NCwxLjQ2My0yLjU3NCwzLjY2M0MxNy4wMTgsMjMuNTk1LDE3LjgyMSwyNC44OTMsMTkuNDI3LDI0Ljg5M1pNMTguMTg0LDIxLjIzYzAtMS41NjIuNDI5LTIuNDUzLDEuMzItMi40NTMuNjQ5LDAsMS4wNDUuNTUsMS4yMjEsMS40NzRsLTIuNTMsMS4zMDlBMy4yLDMuMiwwLDAsMSwxOC4xODQsMjEuMjNaTTE5LjUxNSwyMy45Yy0uNjM4LDAtMS4wNDUtLjUyOC0xLjIyMS0xLjQzbDIuNTMtMS4zMDl2LjI4NkMyMC44MjQsMjMuMDEyLDIwLjQwNiwyMy45LDE5LjUxNSwyMy45WiIgc3R5bGU9ImZpbGw6IzlmNDI0NiIvPjwvc3ZnPg=='
            );
        }

    }

    public function bcvn_myn_general_menu()
    {
        //do nothing at the moment
    }
}