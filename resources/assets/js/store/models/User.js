import { Model } from '@vuex-orm/core';

export default class User extends Model {
  static entity = 'users';

  static fields() {
    return {
      id: this.number(0),
      name: this.string(''),
      business_case_id: this.number(0),
      created_at: this.string(''),
      deleted_at: this.string('').nullable(),
      deleted: this.boolean(),
      email: this.string(''),
      first_name: this.string(''),
      last_name: this.string(''),
      organizational_units: this.attr([]),
      roles: this.attr([]),
      username: this.string(''),
      updated_at: this.string(''),
      updated_by: this.attr({})
    }
  };
};
