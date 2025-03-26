<?php

/**
 * Class to create taskwatermark HTML page output using a skin template
 */
class rcmail_output_taskwatermark extends rcmail_output_html
{
    /**
     * Constructor
     *
     * @param mixed|null $task
     * @param mixed      $framed
     */
    public function __construct($task = null, $framed = false)
    {
        parent::__construct($task, $framed);

        // reset js/css info
        $this->reset(true);
    }

    protected function get_js_commands(&$framed = null)
    {
        return '';
    }
}
