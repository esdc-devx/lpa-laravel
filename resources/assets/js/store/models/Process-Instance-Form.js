import { Model } from '@vuex-orm/core';

export default class Form extends Model {
  static entity = 'forms';

  static fields() {
    return {
      id: this.number(0),
      created_at: this.string(''),
      created_by: this.attr({}),
      current_editor: this.attr({}),
      definition: this.attr({}),
      engine_task_id: this.string(''),
      entity_type: this.string(''),
      form_data: this.attr({}),
      organizational_unit: this.attr({}),
      organizational_unit_id: this.number(0),
      process_form_id: this.number(0),
      process_instance_step_id: this.number(0),
      state: this.attr([]),
      state_id: this.number(0),
      updated_at: this.string(''),
      updated_by: this.attr({}),

      permissions: this.attr({})
    }
  };
};
