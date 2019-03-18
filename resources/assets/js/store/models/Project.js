import { Model } from '@vuex-orm/core';

export default class Project extends Model {
  static entity = 'projects';

  static fields() {
    return {
      id: this.number(0),
      name: this.string(''),
      outline: this.string(''),
      business_case_id: this.number(0),
      created_at: this.string(''),
      created_by: this.attr({}),
      current_process: this.attr({
        definition: {}
      }),
      deleted_at: this.string('').nullable(),
      gate_one_approval_id: this.number(0),
      organizational_unit: this.attr({}),
      organizational_unit_id: this.number(0),
      planned_product_list_id: this.number(0),
      process_instance_id: this.number(0).nullable(),
      state: this.attr({}),
      state_id: this.number(0),
      updated_at: this.string(''),
      updated_by: this.attr({}),

      permissions: this.attr({})
    }
  };
};
