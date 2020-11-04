<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
    <!-- main menu header-->
    <div class="main-menu-header">
        <input type="text" placeholder="Search" class="menu-search form-control round"/>
    </div>
    <!-- / main menu header-->
    <!-- main menu content-->
    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class="<?php echo in_array(Route::current()->getName(), ['admin.home']) ? 'active' : ''; ?> nav-item">
                <a href="{{route('admin.home')}}"><i class="icon-home3"></i><span data-i18n="nav.dash.main" class="menu-title">{{ __('menu.dashboard') }}
                    </span>
                </a>
            </li>
            
            <li class="<?php echo in_array(Route::current()->getName(), ['admin.user.list','admin.user.add','admin.user.edit']) ? 'active' : ''; ?> nav-item">
                <a href="{{route('admin.user.list')}}">
                    <i class="icon-users2"></i><span data-i18n="nav.dash.main" class="menu-title">User Admin</span>
                </a>
            </li>
            
            <li class=" nav-item"><a href="javascript:void(0)">
                <i class="icon-pencil2"></i><span data-i18n="nav.dash.main" class="menu-title">Test Admin</span></a>
                <ul class="menu-content">
                    <li class="<?php echo in_array(Route::current()->getName(), ['admin.course.tests','admin.course.list','admin.course.add','admin.course.edit']) ? 'active' : ''; ?>">
                        <a class="" href="{{route('admin.course.list')}}" data-i18n="nav.dash.main" class="menu-item">Courses</a>
                    </li>
                    <li class="<?php echo in_array(Route::current()->getName(), [
                        'admin.question.list','admin.question.edit',
                        'admin.question.add','admin.test.add','admin.test.list',
                        'admin.test.edit']) ? 'active' : ''; ?>">
                        <a href="{{route('admin.test.list')}}" data-i18n="nav.dash.main" class="menu-item">Modules</a>
                    </li>
                    <li class="<?php echo in_array(Route::current()->getName(), [
                        'admin.chapter.add',
                        'admin.chapter.edit',
                        'admin.chapter.list',
                        'admin.question-bank.index',
                        'admin.question-bank.getChapterQuestion',
                        'admin.question-bank.getChapterQuestions',
                        'admin.question-bank.addChapterQuestions'
                        ]) ? 'active' : ''; ?>">
                        <a href="{{route('admin.chapter.list')}}" data-i18n="nav.dash.main" class="menu-item">Chapters</a>
                    </li>
                    
                    <li class="<?php echo in_array(Route::current()->getName(), [
                        'admin.topics.index',
                        'admin.topics.add',
                        'admin.topics.edit',
                        ]) ? 'active' : ''; ?>">
                        <a href="{{route('admin.topics.index')}}" data-i18n="nav.dash.main" class="menu-item">Topics</a>
                    </li>
                    
                </ul>
            </li>
            
            <li class="<?php echo in_array(Route::current()->getName(), ['admin.filemanager']) ? 'active' : ''; ?> nav-item">
                <a href="javascript:void(0)"><i class="icon-perm_data_setting"></i><span data-i18n="nav.dash.main" class="menu-title">Settings</span></a>
                <ul class="menu-content">
                    <li class="<?php echo in_array(Route::current()->getName(), ['admin.profile']) ? 'active' : ''; ?>">
                        <a href="{{route('admin.profile')}}" data-i18n="nav.dash.main" class="menu-item">{{ __('menu.profile') }}</a>
                    </li>
                    <li class="">
                        <a target="_blank" href="{{route('admin.filemanager')}}" data-i18n="nav.dash.main" class="menu-item">File Manager</a>
                    </li>
                </ul>
            </li>
            
        </ul>
    </div>
    <!-- /main menu content-->
    <!-- main menu footer-->
    <!-- include includes/menu-footer-->
    <!-- main menu footer-->
</div>
