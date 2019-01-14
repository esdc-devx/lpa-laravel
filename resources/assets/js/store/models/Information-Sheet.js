import { Model } from '@vuex-orm/core';

export default class InformationSheet extends Model {
  static entity = 'informationSheets';

  static fields() {
    return {
      id: this.number(0),
      information_sheet_definition_id: this.number(0),
      entity_type: this.string(''),
      entity_id: this.number(0),
      definition: this.attr({}),
      deleted_at: this.string('').nullable(),
    }
  };
};
