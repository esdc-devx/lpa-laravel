import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  getLearningProducts() {
    //@todo uncomment the following line and delete the following instruction. 
    //return axios.get(`projects`);
    return {
      "data": {
        "data": {
          "learning_products": [{
            "id": 1,
            "entity_type": "course",
            "name": "My New Course",
            "type_id": 1,
            "sub_type_id": 2,
            "organizational_unit_id": 4,
            "project_id": null,
            "state_id": 8,
            "process_instance_id": 1,
            "business_case_id": null,
            "business_case_assessment_id": null,
            "architecture_plan_id": null,
            "architecture_plan_assessment_id": null,
            "created_by": null,
            "updated_by": 2,
            "deleted_at": null,
            "created_at": "2018-09-01 15:27:13",
            "updated_at": "2018-09-01 15:27:14",
            "type": {
              "id": 1,
              "parent_id": 0,
              "name_key": "course",
              "active": 1,
              "name": "Course"
            },
            "sub_type": {
              "id": 1,
              "parent_id": 1,
              "name_key": "instructor-led",
              "active": 1,
              "name": "Instructor-led"
            },
            "state": {
              "name_key": "new",
              "name": "New"
            },
            "organizational_unit": {
              "id": 4,
              "parent_id": 0,
              "name_key": "owner-4",
              "active": 1,
              "owner": true,
              "email": "kking@hotmail.com",
              "director": 6,
              "created_at": "2018-09-07 15:23:10",
              "updated_at": "2018-09-07 15:23:10",
              "name": "Management and Professional Development"
            },
            "current_process": {
              "id": 1,
              "entity_type": "course",
              "entity_id": 1,
              "engine_process_instance_id": "05d9c9d6-b2d4-11e8-b02d-34e6d7457435",
              "entity_previous_state_id": 8,
              "created_by": 2,
              "updated_by": 2,
              "deleted_at": null,
              "created_at": "2018-09-07 15:27:14",
              "updated_at": "2018-09-07 15:27:14",
              "definition": {
                "entity_type": "course",
                "name_key": "course-approval",
                "name": "Course Approval"
              }
            }
          },
          {
            "id": 2,
            "entity_type": "course",
            "name": "My 2nd new Course",
            "type_id": 1,
            "sub_type_id": 2,
            "organizational_unit_id": 4,
            "project_id": null,
            "state_id": 8,
            "process_instance_id": 1,
            "business_case_id": null,
            "business_case_assessment_id": null,
            "architecture_plan_id": null,
            "architecture_plan_assessment_id": null,
            "created_by": null,
            "updated_by": 2,
            "deleted_at": null,
            "created_at": "2018-09-02 15:27:13",
            "updated_at": "2018-09-02 15:27:14",
            "type": {
              "id": 1,
              "parent_id": 0,
              "name_key": "course",
              "active": 1,
              "name": "Course"
            },
            "sub_type": {
              "id": 2,
              "parent_id": 1,
              "name_key": "distance",
              "active": 1,
              "name": "Distance Learning"
            },
            "state": {
              "name_key": "new",
              "name": "New"
            },
            "organizational_unit": {
              "id": 2,
              "parent_id": 0,
              "name_key": "owner-4",
              "active": 1,
              "owner": true,
              "email": "kking@hotmail.com",
              "director": 2,
              "created_at": "2018-09-07 15:23:10",
              "updated_at": "2018-09-07 15:23:10",
              "name": "Orientation and Authority Delegation"
            },
            "current_process": {}
          },
          {
            "id": 3,
            "entity_type": "course",
            "name": "My 3rd new Course",
            "type_id": 1,
            "sub_type_id": 3,
            "organizational_unit_id": 4,
            "project_id": null,
            "state_id": 8,
            "process_instance_id": 1,
            "business_case_id": null,
            "business_case_assessment_id": null,
            "architecture_plan_id": null,
            "architecture_plan_assessment_id": null,
            "created_by": null,
            "updated_by": 2,
            "deleted_at": null,
            "created_at": "2018-09-03 15:27:13",
            "updated_at": "2018-09-03 15:27:14",
            "type": {
              "id": 1,
              "parent_id": 0,
              "name_key": "course",
              "active": 1,
              "name": "Course"
            },
            "sub_type": {
              "id": 3,
              "parent_id": 1,
              "name_key": "online",
              "active": 1,
              "name": "Online Self-Paced"
            },
            "state": {
              "name_key": "new",
              "name": "New"
            },
            "organizational_unit": {
              "id": 3,
              "parent_id": 0,
              "name_key": "owner-4",
              "active": 1,
              "owner": true,
              "email": "kking@hotmail.com",
              "director": 3,
              "created_at": "2018-09-07 15:23:10",
              "updated_at": "2018-09-07 15:23:10",
              "name": "Language Training"
            },
            "current_process": {}
          }]
        },
        "meta": []
      },
      "status": 200,
      "statusText": "OK",
      "headers": {
        "date": "Fri, 07 Sep 2018 19:34:23 GMT",
        "server": "Apache/2.4.6 (Red Hat Enterprise Linux) PHP/7.2.8",
        "x-clockwork-version": "2.2.5",
        "x-powered-by": "PHP/7.2.8",
        "x-ratelimit-remaining": "493",
        "connection": "Keep-Alive",
        "content-type": "application/json",
        "cache-control": "no-cache, private",
        "transfer-encoding": "chunked",
        "x-ratelimit-limit": "500",
        "server-timing": "app; dur=948.45008850098; desc=\"Application\", db; dur=13.47; desc=\"Database\", timeline-event-total; dur=948.56119155884; desc=\"Total execution time.\", timeline-event-initialisation; dur=282.77111053467; desc=\"Application initialisation.\", timeline-event-boot; dur=114.5031452179; desc=\"Framework booting.\", timeline-event-run; dur=665.78984260559; desc=\"Framework running.\"",
        "keep-alive": "timeout=5, max=93",
        "x-clockwork-id": "1536348864-0018-1615089712"
      },
      "config": {
        "transformRequest": {},
        "transformResponse": {},
        "timeout": 20000,
        "xsrfCookieName": "XSRF-TOKEN",
        "xsrfHeaderName": "X-XSRF-TOKEN",
        "maxContentLength": -1,
        "headers": {
          "Accept-Language": "fr",
          "X-Requested-With": "XMLHttpRequest",
          "Accept": "application/json",
          "X-CSRF-TOKEN": "xNZuOyaP3MIP6yioh5jJV4hlFjtkaXN8D1CpVc36",
          "X-XSRF-TOKEN": "eyJpdiI6IjBYMWVnd2p5cEhhdE14RXQ0RXJoYmc9PSIsInZhbHVlIjoiMm5JTkhGZ2loTzNXVERnK0xkNDh0anpwbHhXWElrdGZYSm45SXFadmh3Q1NudzBLK3JBTFJvMGl6amFzdzZwWm9sbkgwWWdET1lWZElka2NUblwvYTdnPT0iLCJtYWMiOiI3OTBhODMyMDI5MGI5ODRiZGE2ZTZhNjVjNWZlNGQzNTk2NzdiNjJmZTFiZTkxNDQ3Mzg1ODJiMmE5ZTVhNzVlIn0="
        },
        "baseURL": "/fr/api",
        "method": "get",
        "url": "/fr/api/projects"
      },
      "request": {}
    };
  },

//   getProject(id) {
//     return axios.get(`projects/${id}`);
//   },

//   getProjectCreateInfo() {
//     return axios.get('projects/create');
//   },

//   getProjectEditInfo(id) {
//     return axios.get(`projects/${id}/edit`);
//   },

//   canCreateProject() {
//     return axios.get('authorization/project/create');
//   },

//   canEditProject(id) {
//     return axios.get(`authorization/project/edit/${id}`);
//   },

//   canDeleteProject(id) {
//     return axios.get(`authorization/project/delete/${id}`);
//   },

//   canStartProcess(projectId, processDefinitionNameKey) {
//     return axios.get(`authorization/project/${projectId}/start-process/${processDefinitionNameKey}`);
//   },

//   getProcess(projectId, processId) {
//     return axios.get(`projects/${projectId}/process/${processId}`);
//   },

//   create(project) {
//     return axios.post('projects', project);
//   },

//   update(project) {
//     return axios.put(`projects/${project.id}`, {
//       name: project.name,
//       organizational_unit: project.organizational_unit
//     });
//   },

//   delete(id) {
//     return axios.delete(`projects/${id}`);
//   }
};
