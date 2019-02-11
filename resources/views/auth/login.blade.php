<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('base/navigation.app_name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", "Arial", sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            color: #524f74;
            background-color: #e4edf0;
        }

        .el-header {
            padding: 0;
        }

        /*
         *  The following menu styling sections are inspired from styles found in the top-bar.vue.
         *  Since there are no router for the login page, we must make sure that the default-active
         *  attribute of the <el-menu> from element ui behaves the same way as if we had a router.
         *  Hence, few quirks were required: see li, .el-menu-item, .el-submenu__title
         *
        */
        .top-bar {
            user-select: none;
            padding: 0 20px;
            background-color: #201e2c;
            position: relative;
            /* make space for the side-bar-toggle button */
            padding-left: calc(64px + 15px);
            height: 100%;
            display: flex;
            align-items: center;
        }

        .top-bar .el-menu-item,
        .top-bar .el-submenu .el-submenu__title {
            height: 50px;
            line-height: 50px;
        }

        .top-bar .el-menu {
            display: flex;
            flex-direction: row;
        }

        .top-bar .el-menu > .el-menu-item {
            border: 0px;
            background-color: #201e2c;
        }

        .sub-menu .el-menu,
        .sub-menu .el-menu-item,
        .sub-menu .el-submenu .el-submenu__title {
            color: black !important;
            background-color: white !important;
        }

        .sub-menu .el-menu {
            border-radius: 0;
            border: 1px solid #cdcdcd;
            padding: 0;
            margin-top: 2px;
        }

        .sub-menu .el-menu-item:hover {
            color: white !important;
            background-color: #322f43 !important;
        }

        li:hover,
        li:hover .el-submenu__title {
            background-color: #322f43 !important;
            cursor: pointer;
        }

        .el-submenu__title {
            border: none !important;
        }

        .sub-menu .el-menu-item a {
            display: block;
            width: 100%;
            padding: 0 10px;
            margin: 0 -10px;
            color: black !important;
            background-color: transparent !important;
            text-decoration: none;
            font-weight: normal;
        }

        .sub-menu .el-menu-item a:hover {
            color: white !important;
            cursor: pointer;
        }

        h1 {
            margin: 0;
            color: #fff;
            display: flex;
            width: 100%;
            font-weight: 600;
        }

        .lang {
            transition: all 0.3s;
            display: flex;
            justify-content: flex-end;
            height: 100%;
            align-items: center;
            padding: 0 20px;
            box-sizing: border-box;
            color: #fff;
            text-decoration: none;
            font-weight: normal;
        }

        .lang:hover {
            transition: all 0.3s;
            color: #fff;
            background-color: #322f43;
        }

        .login-wrap {
            width: 600px;
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -40%);
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 4px;
            border: 1px solid #ececec;
        }

        .controls-wrap .el-form-item__content {
            margin: 0 !important;
            text-align: right;
        }

        .el-icon-view:hover, .el-icon-view.active {
            cursor: pointer;
            color: #524f74;
        }
        .cred-warning {
            text-align: center;
        }
        .cred-warning i {
            color: #e6a23c;
        }
    </style>
</head>
<body>
    <div id="app" v-cloak>
        <el-container>
            <el-header height="50px">
                <div class="top-bar">
                    <h1>{{ __('base/navigation.app_name') }}</h1>

                    <el-menu
                        background-color="#201e2c"
                        text-color="#fff"
                        active-text-color="#fff"
                        class="top-menu"
                        mode="horizontal"
                    >
                        <el-submenu index="help-menu" popper-class="sub-menu">
                            <template slot="title">{{ __('base/navigation.help') }}</template>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_support_centre_url') }}" target="_blank">{{ __('base/navigation.help_support_centre') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_getting_started_url') }}" target="_blank">{{ __('base/navigation.help_getting_started') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_projects_url') }}" target="_blank">{{ __('base/navigation.help_projects') }}</a></el-menu-item>
                            <el-menu-item index=""><a href="{{ __('base/navigation.help_learning_products_url') }}" target="_blank">{{ __('base/navigation.help_learning_products') }}</a></el-menu-item>
                        </el-submenu>
                        <el-menu-item index="language"><a :href="'/' + toggledLocale + '/login'" class="lang">{{ __('base/navigation.language_toggle') }}</a></el-menu-item>
                    </el-menu>

                </div>
            </el-header>
            <el-main>
                <div class="login-wrap">
                    <div><h2>{{ __('pages/login.header') }}</h2></div>
                    <p class="cred-warning">
                        <i class="el-icon-warning"></i>
                        {{ __('pages/login.machine_credentials_msg') }}
                    </p>
                    <el-form
                        ref="form"
                        label-width="30%"
                        method="POST"
                        action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <el-form-item label="{{ __('entities/user.username') }}" for="username" :class="['is-required', {'is-error': verrors.collect('username').length }]" prop="username">
                            <el-input id="username" name="username" v-model="username" v-validate="'required'" data-vv-as="{{ __('entities/user.username') }}" @keyup.native.enter="onSubmit" autofocus></el-input>
                            <form-error name="username"></form-error>
                        </el-form-item>
                        <el-form-item label="{{ __('entities/user.password') }}" for="password" :class="['is-required', {'is-error': verrors.collect('password').length }]" prop="password">
                            <el-input id="password" name="password" :type="isPasswordVisible ? 'text' : 'password'" v-model="password" v-validate="'required'" data-vv-as="{{ __('entities/user.password') }}" @keyup.native.enter="onSubmit">
                                <i
                                    class="el-icon-view el-input__icon"
                                    slot="suffix"
                                    @mousedown="isPasswordVisible = true"
                                    @mouseup="isPasswordVisible = false">
                                </i>
                            </el-input>
                            <form-error name="password"></form-error>
                        </el-form-item>
                        <el-form-item for="remember">
                            <el-checkbox name="remember" name="remember" v-model="remember" @keyup.native.enter="onSubmit" label="{{ __('pages/login.remember') }}"></el-checkbox>
                        </el-form-item>

                        <el-form-item class="controls-wrap">
                            <el-button type="primary" :loading="isSubmitting" @click="onSubmit">{{ __('pages/login.login') }}</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-main>
        </el-container>
    </div>
    <div class="loading-spinner">
        <svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path"></circle></svg>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/login.js') }}"></script>
</body>
</html>
