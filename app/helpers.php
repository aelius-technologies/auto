<?php
    if (!function_exists('_settings')) {
        function _settings($key = '') {
            if($key == '')
                return NULL;

            $data = DB::table('settings')->select('value')->where(['key' => $key])->first();

            if(!empty($data))
                return $data->value;
            else
                return NULL;
        }
    }

    if (!function_exists('_path')) {
        function _path($folder = '') {
            if($folder == '')
                return asset('/uploads/').'/';
            else
                return asset('/uploads/').'/'.$folder.'/';
        }
    }

    if (!function_exists('_fevicon')) {
        function _fevicon() {
            $data = DB::table('settings')->select('value')->where(['key' => 'FEVICON'])->first();

            if(!empty($data))
                return _path('logo').$data->value;
            else
                return NULL;
        }
    }

    if (!function_exists('_logo')) {
        function _logo() {
            $data = DB::table('settings')->select('value')->where(['key' => 'LOGO'])->first();

            if(!empty($data))
                return _path('logo').$data->value;
            else
                return NULL;
        }
    }

    if (!function_exists('_small_logo')) {
        function _small_logo() {
            $data = DB::table('settings')->select('value')->where(['key' => 'SMALL_LOGO'])->first();

            if(!empty($data))
                return _path('logo').$data->value;
            else
                return NULL;
        }
    }
?>