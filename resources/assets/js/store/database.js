import { Database } from '@vuex-orm/core';

import Project from './models/Project';
import Process from './models/Process';
import ProcessInstanceForm from './models/Process-Instance-Form';
import OrganizationalUnit from './models/Organizational-Unit';
import InformationSheet from './models/Information-Sheet';

import projects from './modules/projects';
import processes from './modules/processes';
import processInstanceForms from './modules/process-instance-forms';
import organizationalUnits from './modules/organizational-units';
import informationSheets from './modules/information-sheets';

const database = new Database();
database.register(Project, projects);
database.register(Process, processes);
database.register(ProcessInstanceForm, processInstanceForms);
database.register(OrganizationalUnit, organizationalUnits);
database.register(InformationSheet, informationSheets);

export default database;
