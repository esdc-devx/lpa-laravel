<template>
  <div class="material">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.quantity`"
      contextPath="forms.operational_details.materials.quantity"
      required
    >
      <input-wrap
        v-model="form.quantity"
        name="quantity"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="trans('forms.operational_details.materials.quantity.label')"
        type="number"
        :min="1"
        :max="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.material_item_id`"
      :classes="['has-other']"
      contextPath="forms.operational_details.materials.material_item"
      required
    >
      <el-select-other-wrap
        :modelSelect.sync="form.material_item_id"
        nameSelect="material_item_id"
        :dataVvAsSelect="trans('forms.operational_details.materials.material_item.label')"
        :validateSelect="{ required: !this.isMaterialItemOther }"
        :options="data.materialItemList"
        sorted

        localizable
        :modelOtherEn.sync="form.material_item_other_en"
        :modelOtherFr.sync="form.material_item_other_fr"
        nameOtherEn="material_item_other_en"
        nameOtherFr="material_item_other_fr"
        :dataVvAsOtherEn="trans('forms.operational_details.materials.material_item_other.label')"
        :dataVvAsOtherFr="trans('forms.operational_details.materials.material_item_other.label')"
        :validateOther="{ required: this.isMaterialItemOther }"
        :isChecked.sync="isMaterialItemOther"
        maxlength="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.material_denominator_id`"
      contextPath="forms.operational_details.materials.material_denominator"
      required
    >
      <el-select-wrap
        v-model="form.material_denominator_id"
        v-validate="'required'"
        :data-vv-as="trans('forms.operational_details.materials.material_denominator.label')"
        name="material_denominator_id"
        :options="data.materialDenominatorList"
        sorted
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.material_location_id`"
      contextPath="forms.operational_details.materials.material_location"
      required
    >
      <el-select-wrap
        v-model="form.material_location_id"
        v-validate="'required'"
        :data-vv-as="trans('forms.operational_details.materials.material_location.label')"
        name="material_location_id"
        :options="data.materialLocationList"
        sorted
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.reusable`"
      contextPath="forms.operational_details.materials.reusable"
      required
    >
      <yes-no
        v-model="form.reusable"
        v-validate="'required'"
        :data-vv-as="trans('forms.operational_details.materials.reusable.label')"
        name="reusable"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.notes`"
      contextPath="forms.operational_details.materials.notes"
    >
      <input-localized
        :modelEn.sync="form.notes_en"
        :modelFr.sync="form.notes_fr"
        :validate="''"
        :dataVvAsEn="trans('forms.operational_details.materials.notes.label')"
        :dataVvAsFr="trans('forms.operational_details.materials.notes.label')"
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
    name: 'material',

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
        return 'materials.' + this.index;
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
          material_denominator_id: null,
          material_location_id: null,
          reusable: null,
          notes_en: null,
          notes_fr: null
        },
        isMaterialItemOther: false
      };
    },

    watch: {
      form: function () {
        // make the checkbox react when the form data changes
        this.isMaterialItemOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
      }
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isMaterialItemOther = !!this.form.material_item_other_en || !!this.form.material_item_other_fr;
    }
  };
</script>

<style lang="scss">

</style>
