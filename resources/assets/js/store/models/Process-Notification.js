import { Model } from '@vuex-orm/core';

export default class ProcessNotification extends Model {
  static entity = 'processNotifications';

  static fields() {
    return {
      id: this.number(0),
      process_definition_id: this.number(0),
      process_definition: this.attr({}),
      name_key: this.string(''),
      name: this.string(''),
      name_en: this.string(''),
      name_fr: this.string(''),
      subject: this.string(''),
      subject_en: this.string(''),
      subject_fr: this.string(''),
      body: this.string(''),
      body_en: this.string(''),
      body_fr: this.string(''),
      deleted_at: this.string('').nullable(),
      created_at: this.string(''),
      created_by: this.attr({}),
      updated_at: this.string(''),
      updated_by: this.attr({})
    }
  };
};
