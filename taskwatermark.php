<?php

/**
 * TaskWatermark
 *
 * Plugin to create task aware watermark screen
 *
 * @author Philip Weir
 *
 * Copyright (C) Philip Weir
 *
 * This program is a Roundcube (https://roundcube.net) plugin.
 * For more information see README.md.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Roundcube. If not, see https://www.gnu.org/licenses/.
 */
class taskwatermark extends rcube_plugin
{
    public $task = '?(?!login$|logout$|cli$).*';
    private $rcube;

    public function init()
    {
        $this->rcube = rcube::get_instance();

        if (is_object($this->rcube->output) && $this->rcube->output->type == 'html') {
            // check template exists
            $this->template_path = '/' . $this->local_skin_path() . '/templates/taskwatermark.html';
            $filepath = slashify($this->home) . $this->template_path;
            if (is_file($filepath) && is_readable($filepath)) {
                // use full URL because of URL comparison in Elastic skin
                $url = $this->rcube->url(['_action' => 'plugin.taskwatermark.show', '_src' => !empty($this->rcube->action) ? $this->rcube->action : 'list'], false, true);
                $this->rcube->output->set_env('blankpage', $url);
                $this->register_action('plugin.taskwatermark.show', [$this, 'show']);
            }

            if ($this->rcube->task == 'mail' && $this->rcube->action == '') {
                $this->rcube->output->set_env('display_first', $this->rcube->config->get('display_first', false));
                $this->include_script('taskwatermark.js');
            }
        }

        $this->add_hook('preferences_list', [$this, 'preferences_list']);
        $this->add_hook('preferences_save', [$this, 'preferences_save']);
        $this->register_action('plugin.taskwatermark.enable', [$this, 'enable']);
    }

    public function show()
    {
        // Add include path for internal classes
        $include_path = $this->home . '/include' . \PATH_SEPARATOR;
        $include_path .= ini_get('include_path');
        set_include_path($include_path);

        $output = new rcmail_output_taskwatermark();

        $output->add_handler('plugin.body', [$this, 'body']);

        $this->api->output = $output;
        $this->include_stylesheet($this->local_skin_path() . '/taskwatermark.css');
        $output->send('taskwatermark.taskwatermark');
    }

    public function body($attrib)
    {
        // sanitise original action
        $action = asciiwords(rcube_utils::get_input_string('_src', rcube_utils::INPUT_GPC));
        $action = str_replace('.', '_', $action);

        $this->add_texts('localization/');

        // task specific hints
        $hint = '';
        if ($this->rcube->task == 'mail' && $action == 'list') {
            $hint = 'mailtip';
        } elseif ($this->rcube->task == 'addressbook' && $action == 'list') {
            $hint = 'contacttip';
        } elseif ($this->rcube->task == 'settings') {
            switch ($action) {
                case 'list':
                case 'preferences':
                    $hint = 'settingstip';
                    break;
                case 'folders':
                    $hint = 'folderstip';
                    break;
                case 'identities':
                    $hint = 'identitiestip';
                    break;
                case 'responses':
                    $hint = 'responsestip';
                    break;
                case 'plugin_managesieve':
                    $hint = 'filterstip';
                    break;
                case 'plugin_enigmakeys':
                    $hint = 'enigmatip';
                    break;
            }
        }

        $data = $this->rcube->plugins->exec_hook('taskwatermark_show', ['action' => $action, 'hint' => $hint]);

        if (!empty($data['output'])) {
            $out = $data['output'];
        } else {
            $classes = '';
            $classes .= ' task-' . (!empty($this->rcube->task) ? $this->rcube->task : 'error');
            $classes .= ' action-' . $action;

            $out = '';
            $out .= html::div('watermark' . $classes, '');
            $out .= html::div('hint' . $classes, !empty($data['hint']) ? rcmail::Q($this->gettext($data['hint'])) : '');

            // Add auto open option for mail view
            $no_override = array_flip((array) $this->rcube->config->get('dont_override'));
            if ($this->rcube->task == 'mail' && $action == 'list' && !$this->rcube->config->get('display_first', false) && !isset($no_override['display_first'])) {
                $out .= html::a(['href' => '#', 'onclick' => 'return parent.' . rcmail_output::JS_OBJECT_NAME . '.taskwatermark_enable();'], rcmail::Q($this->gettext('clickdisplayfirst')));
            }
        }

        return $out;
    }

    public function enable()
    {
        $this->rcube->user->save_prefs(['display_first' => true]);
        $this->rcube->output->set_env('display_first', true);
        $this->rcube->output->command('message_list.select_first');
    }

    public function preferences_list($p)
    {
        if ($p['section'] == 'mailview') {
            $no_override = array_flip((array) $this->rcube->config->get('dont_override'));

            if (!isset($no_override['display_first'])) {
                $this->add_texts('localization/');

                $field_id = 'rcmfd_displayfirst';
                $input = new html_checkbox(['name' => '_display_first', 'id' => $field_id, 'value' => 1]);

                $p['blocks']['main']['options']['display_first'] = [
                    'title' => html::label($field_id, rcube::Q($this->gettext('displayfirst'))),
                    'content' => $input->show($this->rcube->config->get('display_first', false)),
                ];

                return $p;
            }
        }
    }

    public function preferences_save($p)
    {
        if ($p['section'] == 'mailview') {
            $p['prefs']['display_first'] = isset($_POST['_display_first']);

            return $p;
        }
    }
}
