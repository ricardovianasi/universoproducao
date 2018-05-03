<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 03/05/2018
 * Time: 09:37
 */

namespace Admin\Form\SessionSchool;

use Admin\Form\Programing\ProgramingForm;

class SessionSchoolProgramingForm extends ProgramingForm
{
    public function __construct($em, $event=null)
    {
        parent::__construct($em, $event);
    }
}