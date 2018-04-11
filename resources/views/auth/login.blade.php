<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('navigation.app_name') }}</title>

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
                    <h1>{{ __('navigation.app_name') }}</h1>
                    <a v-if="toggledLocale === 'fr'" :href="'/' + toggledLocale + '/login'" class="lang">Français</a>
                    <a v-else :href="'/' + toggledLocale + '/login'" class="lang">English</a>
                </div>
            </el-header>
            <el-main>
                <div class="login-wrap">
                    <div><h2>{{ __('login.title') }}</h2></div>
                    <p class="cred-warning">
                        <i class="el-icon-warning"></i>
                        Your credentials are the same as your machine ones.
                    </p>
                    <el-form
                        ref="form"
                        label-width="30%"
                        method="POST"
                        action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <el-form-item label="{{ __('login.username') }}" for="username" :class="['is-required', {'is-error': verrors.collect('username').length }]" prop="username">
                            <el-input id="username" name="username" v-model="username" v-validate="'required'" @keyup.native.enter="onSubmit" autofocus></el-input>
                            <form-error v-for="error in verrors.collect('username')" :key="error.id">@{{ error }}</form-error>
                        </el-form-item>
                        <el-form-item label="{{ __('login.password') }}" for="password" :class="['is-required', {'is-error': verrors.collect('password').length }]" prop="password">
                            <el-input id="password" name="password" :type="isPasswordVisible ? 'text' : 'password'" v-model="password" v-validate="'required'" @keyup.native.enter="onSubmit">
                                <i
                                    class="el-icon-view el-input__icon"
                                    slot="suffix"
                                    @mousedown="isPasswordVisible = true"
                                    @mouseup="isPasswordVisible = false">
                                </i>
                            </el-input>
                            <form-error v-for="error in verrors.collect('password')" :key="error.id">@{{ error }}</form-error>
                        </el-form-item>
                        <el-form-item for="remember">
                            <el-checkbox name="remember" name="remember" v-model="remember" @keyup.native.enter="onSubmit" label="{{ __('login.remember') }}"></el-checkbox>
                        </el-form-item>

                        <el-form-item class="controls-wrap">
                            <el-button type="primary" :loading="isSaving" @click="onSubmit">{{ __('login.login') }}</el-button>
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
