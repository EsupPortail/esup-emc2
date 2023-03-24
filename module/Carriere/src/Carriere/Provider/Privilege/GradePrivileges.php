<?php

namespace Carriere\Provider\Privilege;

use UnicaenPrivilege\Provider\Privilege\Privileges;

class GradePrivileges extends Privileges
{
    const GRADE_INDEX    = 'grade-grade_index';
    const GRADE_AFFICHER = 'grade-grade_afficher';
    const GRADE_MODIFIER = 'grade-grade_modifier';
    const GRADE_LISTER_AGENTS = 'grade-grade_lister_agents';
}