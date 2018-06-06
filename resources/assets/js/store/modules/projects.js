import ProjectsAPI from '../../api/projects';

export default {
  namespaced: true,

  state: {
    // project being viewed
    viewing: {},
    processes: [],
    currentProcess: {
      definition: {},
      state: {}
    },
    all: [],
    organizationalUnits: []
  },

  getters: {
    all(state) {
      return state.all;
    },

    viewing(state) {
      return state.viewing;
    },

    processes(state) {
      return state.processes;
    },

    currentProcess(state) {
      return state.currentProcess;
    },

    organizationalUnits(state) {
      return state.organizationalUnits;
    }
  },

  actions: {
    async loadProjectCreateInfo({ commit }) {
      let response = await ProjectsAPI.getProjectCreateInfo();
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async loadProjectEditInfo({ commit }, id) {
      let response = await ProjectsAPI.getProjectEditInfo(id);
      commit('setViewingProject', response.data.data.project);
      commit('setOrganizationalUnits', response.data.data.organizational_units);
      return response.data.data;
    },

    async loadProjects({ commit, dispatch }) {
      let response = await ProjectsAPI.getProjects();
      commit('setProjects', response.data.data.projects);
      return response.data.data.projects;
    },

    async loadProject({ commit }, id) {
      let response = await ProjectsAPI.getProject(id);
      commit('setViewingProject', response.data.data.project);
      commit('setProcesses', response.data.data.processes);
      commit('setCurrentProcess', response.data.data.project.current_process);
      return response.data.data;
    },

    async loadProcess({ commit }, { projectId, processId }) {
      let response = await ProjectsAPI.getProcess(projectId, processId);
      commit('setCurrentProcess', response.data.data.process_instance);
      return response.data.data;
    },

    async canCreateProject({ commit }) {
      let response = await ProjectsAPI.canCreateProject();
      return response.data.data.allowed;
    },

    async canEditProject({ commit }, id) {
      let response = await ProjectsAPI.canEditProject(id);
      return response.data.data.allowed;
    },

    async canDeleteProject({ commit }, id) {
      let response = await ProjectsAPI.canDeleteProject(id);
      return response.data.data.allowed;
    },

    async canStartProcess({ commit }, { projectId, processNameKey }) {
      let response = await ProjectsAPI.canStartProcess(projectId, processNameKey);
      return response.data.data.allowed;
    },

    async createProject({ commit }, project) {
      let response = await ProjectsAPI.createProject(project);
      return response.data.data;
    },

    async startProcess({ commit }, { projectId, processNameKey }) {
      let response = await ProjectsAPI.startProcess(projectId, processNameKey);
      return response.data.data;
    },

    async updateProject({ commit }, project) {
      await ProjectsAPI.updateProject(project);
    },

    async deleteProject({ commit }, id) {
      await ProjectsAPI.deleteProject(id);
    }
  },

  mutations: {
    setProjects(state, projects) {
      state.all = projects;
    },

    setProcesses(state, processes) {
      state.processes = processes;
    },

    setCurrentProcess(state, currentProcess) {
      // set to default in case there is no process yet
      currentProcess = currentProcess || state.currentProcess;
      state.currentProcess = currentProcess;
    },

    setViewingProject(state, project) {
      state.viewing = project;
    },

    setOrganizationalUnits(state, organizationalUnits) {
      state.organizationalUnits = organizationalUnits;
    }
  }
};
