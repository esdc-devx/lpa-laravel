import { Model } from '@vuex-orm/core';

export default class LearningProduct extends Model {
  static entity = 'learningProducts';

  static fields() {
    return {
      id: this.number(0),
      name: this.string(''),
      created_at: this.string(''),
      created_by: this.attr({}),
      current_process: this.attr({
        definition: {}
      }),
      deleted_at: this.string('').nullable(),
      entity_type: this.string(''),
      manager: this.attr({}),
      organizational_unit: this.attr({}),
      organizational_unit_id: this.number(0),
      primary_contact: this.attr({}),
      project_id: this.number(0),
      process_instance_id: this.number(0).nullable(),
      state: this.attr({}),
      state_id: this.number(0),
      sub_type: this.attr({}),
      sub_type_id: this.number(0).nullable(),
      type: this.attr({}),
      type_id: this.number(0),
      updated_at: this.string(''),
      updated_by: this.attr({}),

      permissions: this.attr({})
    }
  };
};
