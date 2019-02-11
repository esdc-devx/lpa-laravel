import { Database } from '@vuex-orm/core';

import Project from './models/Project';
import LearningProduct from './models/Learning-Product';
import Process from './models/Process';
import ProcessInstanceForm from './models/Process-Instance-Form';
import ProcessNotification from './models/Process-Notification';
import OrganizationalUnit from './models/Organizational-Unit';
import InformationSheet from './models/Information-Sheet';
import User from './models/User';

import projects from './modules/projects';
import learningProducts from './modules/learning-products';
import processes from './modules/processes';
import processInstanceForms from './modules/process-instance-forms';
import processNotifications from './modules/process-notifications';
import organizationalUnits from './modules/organizational-units';
import informationSheets from './modules/information-sheets';
import users from './modules/users';

const database = new Database();
database.register(Project, projects);
database.register(LearningProduct, learningProducts);
database.register(Process, processes);
database.register(ProcessInstanceForm, processInstanceForms);
database.register(ProcessNotification, processNotifications);
database.register(OrganizationalUnit, organizationalUnits);
database.register(InformationSheet, informationSheets);
database.register(User, users);

export default database;
