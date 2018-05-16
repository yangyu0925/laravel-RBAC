@extends('admin.layouts.auth')

@section('admin-auth-page-container')
    <div id="page-container" class="fade">
        <!-- begin login -->
        <div class="login login-with-news-feed">
            <!-- begin news-feed -->
            <div class="news-feed">
                <div class="news-image">
                    <img src="{{ asset('asset_admin/assets/img/login-bg/bg-7.jpg') }}" data-id="login-cover-image" alt="" />
                </div>
                <div class="news-caption">
                    <h4 class="caption-title"><i class="fa fa-diamond text-success"></i> Announcing the Color Admin app</h4>
                    <p>
                        Download the Color Admin app for iPhone®, iPad®, and Android™. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    </p>
                </div>
            </div>
            <!-- end news-feed -->
            <!-- begin right-content -->
            <div class="right-content">
                <!-- begin login-header -->
                <div class="login-header">
                    <div class="brand">
                        <span class="logo"></span> Color Admin
                        <small>responsive bootstrap 3 admin template</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <!-- end login-header -->
                <!-- begin login-content -->
                <div class="login-content">
                    @if(count($errors)>0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('admin/login') }}" method="POST" class="margin-bottom-0">
                        {{ csrf_field() }}
                        <div class="form-group m-b-15">
                            <input type="text" name="email" class="form-control input-lg" placeholder="邮箱地址" value="{{ old('email') }}"/>
                        </div>
                        <div class="form-group m-b-15">
                            <input type="password" name="password" class="form-control input-lg" placeholder="密码" />
                        </div>
                        <div class="checkbox m-b-30">
                            <label>
                                <input name="remember" type="checkbox" /> 记住密码
                            </label>
                        </div>
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg">登　录</button>
                        </div>
                        <div class="m-t-20 m-b-40 p-b-40">
                            Not a member yet? Click <a href="register_v3.html" class="text-success">here</a> to register.
                        </div>
                        <hr />
                        <p class="text-center text-inverse">
                            &copy; Color Admin All Right Reserved 2015
                        </p>
                    </form>
                </div>
                <!-- end login-content -->
            </div>
            <!-- end right-container -->
        </div>
        <!-- end login -->

    </div>
@endsection