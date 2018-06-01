<template>
  <div class="process-current-bar">
    <ul class="process-current-bar-list">
      <li class="process-current-bar-list-item"><span class="process-current-bar-list-item-label">{{ trans(`entities['${entityType}'].status`) }}</span> <span class="process-current-bar-list-item-value">{{ dataProp.state.name }}</span></li>
      <li class="process-current-bar-list-item"><span class="process-current-bar-list-item-label">{{ trans('entities.general.current_process') }}</span> <span class="process-current-bar-list-item-value">{{ dataProp.current_process ? dataProp.current_process.definition.name : trans('entities.general.na') }}</span></li>
    </ul>
    <div class="controls">
      <el-button v-if="dataProp.current_process" type="success" size="mini" @click="continueToProcess(dataProp.current_process.id)">{{ trans('entities.process.view') }} <i class="el-icon-arrow-right"></i></el-button>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'process-current-bar',

    props: [ 'data', 'type' ],

    computed: {
      dataProp() {
        return this.data;
      },
      entityType() {
        return this.type;
      }
    },

    methods: {
      continueToProcess(processId) {
        let id = this.$route.params[`${this.entityType}Id`];
        this.$router.push(`${id}/process/${processId}`);
      }
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';
  @import '../../sass/abstracts/functions';

  // https://codepen.io/fret2buzz/pen/zEyGwV
  $arrow-width: 10px;
  $arrow-height: 32px;

  @mixin arrow($fill, $stroke) {
    // remove px unit by dividing by 1px
    $w: $arrow-width / 1px;
    $h: $arrow-height / 1px;
    $fill: encodeColor($fill);
    $stroke: encodeColor($stroke);
    $image: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' preserveAspectRatio='none' viewBox='0 0 #{$w} #{$h}'%3E%3Cpath d='M0 0, L#{$w - 1} #{$h / 2}, L0 #{$h}' style='stroke: #{$stroke}; fill: #{$fill}; stroke-width: 1' /%3E%3C/svg%3E";
    background-image: url($image);
  }

  .process-current-bar {
    display: flex;
    margin-bottom: 20px;
    &-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      overflow: hidden;
      padding-right: 10px;
      &-item {
        position: relative;
        border-right: 0;
        display: flex;
        background-color: $--color-white;
        border: 1px solid $--border-color-lighter;
        &:before, &:after {
          content: '';
        }
        &:before {
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          border-left: 0;
          border-right: 0;
        }
        &:after {
          @include arrow($--color-white, $--border-color-base);
          position: absolute;
          top: -1px;
          right: -#{$arrow-width};
          z-index: 1;
          width: $arrow-width;
          height: calc(100% + 2px);
          background-size: 100% 100%;
          background-position: 0 50%;
          background-repeat: no-repeat;
        }
        &:first-child {
          border-top-left-radius: $--border-radius-base;
          border-bottom-left-radius: $--border-radius-base;
          &-label {
            padding-left: 10px;
          }
        }
        &-label, &-value {
          overflow: hidden;
        }
        &-label {
          font-weight: bold;
          margin: 2px 5px 2px (5px + $arrow-width);
        }
        &-value {
          margin: 2px (5px + $arrow-width) 2px 5px;
        }
      }
    }

    .controls {
      margin-left: 10px;
      margin-bottom: 0;
      display: inline-block;
    }
  }
</style>
