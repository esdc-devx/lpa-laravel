import { Model } from '@vuex-orm/core';

export default class Process extends Model {
  static entity = 'processes';

  static fields() {
    return {
      id: this.number(0),
      created_at: this.string(''),
      created_by: this.attr({}),
      definition: this.attr({}),
      deleted_at: this.string('').nullable(),
      engine_process_instance_id: this.string(''),
      entity_id: this.number(0),
      entity_previous_state_id: this.number(0),
      entity_type: this.string(''),
      process_definition_id: this.number(0),
      send_notifications: this.boolean(false),
      state: this.attr({}),
      state_id: this.number(0),
      steps: this.attr([]),
      updated_at: this.string(''),
      updated_by: this.attr({}),

      permissions: this.attr({})
    }
  };
};
