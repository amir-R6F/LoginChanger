<?php

function auth_setting($key = ''){

    $options = get_option('am_auth_setting');

    return isset($options[$key]) ? $options[$key] : null;
}
