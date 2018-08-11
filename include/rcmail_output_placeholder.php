<?php

/**
 * Class to create placeholder HTML page output using a skin template
 *
 */
class rcmail_output_placeholder extends rcmail_output_html
{
    /**
     * Constructor
     */
    public function __construct($task = null, $framed = false)
    {
        parent::__construct();

        // reset js/css info
        $this->reset(true);
    }

    protected function get_js_commands(&$framed = null)
    {
        return '';
    }
}
