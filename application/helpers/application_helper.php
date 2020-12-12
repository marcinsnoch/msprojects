<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('array_from_post')) {
    function array_from_post(array $fields)
    {
        $ci = &get_instance();
        $data = [];

        foreach ($fields as $field) {
            $input = $ci->input->post($field);

            if ($input == '' || $input == false) {
                $data[$field] = null;
            } else {
                $data[$field] = $input;
            }
        }

        return $data;
    }
}

if (!function_exists('set_alert')) {
    function set_alert($type, $msg)
    {
        $ci = &get_instance();
        $ci->session->set_flashdata('alert', ['type' => $type, 'msg' => $msg]);
    }
}

// End of file application_helper.php
// Location: ./application/helpers/application_helper.php
