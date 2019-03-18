<template>
  <div class="document">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.quantity`"
      contextPath="forms.operational_details.documents.quantity"
      required
    >
      <input-wrap
        v-model="form.quantity"
        name="quantity"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.operational_details.documents.quantity.label')"
        type="number"
        :min="1"
        :max="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.document_category_id`"
      :classes="['has-other']"
      contextPath="forms.operational_details.documents.document_category"
      required
    >
      <el-select-other-wrap
        :modelSelect.sync="form.document_category_id"
        nameSelect="document_category_id"
        :dataVvAsSelect="trans('forms.operational_details.documents.document_category.label')"
        :validateSelect="{ required: !this.isDocumentCategoryOther }"
        :options="data.documentCategoryList"
        sorted

        localizable
        :modelOtherEn.sync="form.document_category_other_en"
        :modelOtherFr.sync="form.document_category_other_fr"
        nameOtherEn="document_category_other_en"
        nameOtherFr="document_category_other_fr"
        :dataVvAsOtherEn="trans('forms.operational_details.documents.document_category_other.label')"
        :dataVvAsOtherFr="trans('forms.operational_details.documents.document_category_other.label')"
        :validateOther="{ required: this.isDocumentCategoryOther }"
        :isChecked.sync="isDocumentCategoryOther"
        maxlength="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.document_denominator_id`"
      contextPath="forms.operational_details.documents.document_denominator"
      required
    >
      <el-select-wrap
        v-model="form.document_denominator_id"
        v-validate="'required'"
        :data-vv-as="trans('forms.operational_details.documents.document_denominator.label')"
        name="document_denominator_id"
        :options="data.documentDenominatorList"
        sorted
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.title`"
      contextPath="forms.operational_details.documents.title"
      required
    >
      <input-localized
        :modelEn.sync="form.title_en"
        :modelFr.sync="form.title_fr"
        :validate="'required'"
        :dataVvAsEn="trans('forms.operational_details.documents.title.label')"
        :dataVvAsFr="trans('forms.operational_details.documents.title.label')"
        :nameEn="`${fieldNamePrefix}.title_en`"
        :nameFr="`${fieldNamePrefix}.title_fr`"
        maxlength="250"
        type="textarea"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.version`"
      contextPath="forms.operational_details.documents.version"
      required
    >
      <input-wrap
        v-model="form.version"
        name="version"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.operational_details.documents.version.label')"
        type="number"
        :min="1"
        :max="10"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.link`"
      contextPath="forms.operational_details.documents.link"
      required
    >
      <input-localized
        :modelEn.sync="form.link_en"
        :modelFr.sync="form.link_fr"
        :validate="'required'"
        :dataVvAsEn="trans('forms.operational_details.documents.link.label')"
        :dataVvAsFr="trans('forms.operational_details.documents.link.label')"
        :nameEn="`${fieldNamePrefix}.link_en`"
        :nameFr="`${fieldNamePrefix}.link_fr`"
        maxlength="2048"
        type="textarea"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.printing_specifications`"
      contextPath="forms.operational_details.documents.printing_specifications"
    >
      <input-localized
        :modelEn.sync="form.printing_specifications_en"
        :modelFr.sync="form.printing_specifications_fr"
        :validate="''"
        :dataVvAsEn="trans('forms.operational_details.documents.printing_specifications.label')"
        :dataVvAsFr="trans('forms.operational_details.documents.printing_specifications.label')"
        :nameEn="`${fieldNamePrefix}.printing_specifications_en`"
        :nameFr="`${fieldNamePrefix}.printing_specifications_fr`"
        maxlength="1250"
        type="textarea"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.reusable`"
      contextPath="forms.operational_details.documents.reusable"
      required
    >
      <yes-no
        v-model="form.reusable"
        v-validate="'required'"
        :data-vv-as="trans('forms.operational_details.documents.reusable.label')"
        name="reusable"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.notes`"
      contextPath="forms.operational_details.documents.notes"
    >
      <input-localized
        :modelEn.sync="form.notes_en"
        :modelFr.sync="form.notes_fr"
        :validate="''"
        :dataVvAsEn="trans('forms.operational_details.documents.notes.label')"
        :dataVvAsFr="trans('forms.operational_details.documents.notes.label')"
        :nameEn="`${fieldNamePrefix}.notes_en`"
        :nameFr="`${fieldNamePrefix}.notes_fr`"
        maxlength="500"
        type="textarea"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import ElSelectOtherWrap from '../el-select-other-wrap';
  import InputWrap from '../input-wrap';
  import InputLocalized from '../input-localized';
  import YesNo from '../yes-no';

  export default {
    name: 'document',

    components: { ElFormItemWrap, ElSelectWrap, ElSelectOtherWrap, InputWrap, InputLocalized, YesNo },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      data: {
        type: Object,
        required: true
      },
      index: {
        type: Number,
        required: true
      },
      value: {
        type: Object
      }
    },

    computed: {
      fieldNamePrefix() {
        return 'documents.' + this.index;
      },
      form: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          quantity: null,
          material_item_id: null,
          material_item_other_en: null,
          material_item_other_fr: null,
          document_denominator_id: null,
          title_en: null,
          title_fr: null,
          version: null,
          link_en: null,
          link_fr: null,
          printing_specifications_en: null,
          printing_specifications_fr: null,
          reusable: null,
          notes_en: null,
          notes_fr: null
        },
        isDocumentCategoryOther: false
      };
    },

    watch: {
      form: function () {
        // make the checkbox react when the form data changes
        this.isDocumentCategoryOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
      }
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isDocumentCategoryOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
    }
  };
</script>

<style lang="scss">

</style>
