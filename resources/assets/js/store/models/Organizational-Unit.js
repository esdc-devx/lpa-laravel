import { Model } from '@vuex-orm/core';

export default class OrganizationalUnit extends Model {
  static entity = 'organizationalUnits';

  static fields() {
    return {
      id: this.number(0),
      parent_id: this.number(0),
      name_key: this.string(''),
      name: this.string(''),
      name_en: this.string(''),
      name_fr: this.string(''),
      active: this.boolean(),
      owner: this.boolean(),
      email: this.string(''),
      director: this.attr({}),
      deleted_at: this.string('').nullable(),
      created_at: this.string(''),
      created_by: this.attr({}),
      updated_at: this.string(''),
      updated_by: this.attr({})
    }
  };
};
