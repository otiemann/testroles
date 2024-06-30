<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Block testroles is defined here.
 *
 * @package     block_testroles
 * @copyright   2024 Oliver Tiemann mail@olivertiemann.eu
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_testroles extends block_base {

    public function init() {
        $this->title = get_string('testroles', 'block_testroles');
    }

    public function get_content() {
        global $USER, $COURSE, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';

        if (isloggedin() && !isguestuser()) {
            $context = context_course::instance($COURSE->id);
            $roles = get_user_roles($context, $USER->id);

            if ($roles) {
                $this->content->text .= '<ul>';
                foreach ($roles as $role) {
                    $roleName = $DB->get_record('role', array('id' => $role->roleid), 'shortname, name');
                    $this->content->text .= '<li>' . format_string($roleName->name) . ' (' . format_string($roleName->shortname) . ')</li>';
                }
                $this->content->text .= '</ul>';
            } else {
                $this->content->text .= get_string('noroles', 'block_testroles');
            }
        } else {
            $this->content->text .= get_string('notloggedin', 'block_testroles');
        }

        return $this->content;
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }
}